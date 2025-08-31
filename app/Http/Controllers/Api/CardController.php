<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    /**
     * Get user's cards.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $cards = $user->cards()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $cards
        ]);
    }

    /**
     * Add a new card.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'card_number' => 'required|string|min:13|max:19',
            'expiry_date' => 'required|string|regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/',
            'cardholder_name' => 'required|string|max:255',
            'is_default' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate card number format
        $cardNumber = preg_replace('/\D/', '', $request->card_number);
        if (!preg_match('/^[0-9]{13,19}$/', $cardNumber)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid card number format'
            ], 422);
        }

        // Validate expiry date
        if (!Card::validateExpiryDate($request->expiry_date)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired card'
            ], 422);
        }

        // Detect card type
        $cardType = Card::detectCardType($cardNumber);
        if ($cardType === 'unknown') {
            return response()->json([
                'success' => false,
                'message' => 'Unsupported card type'
            ], 422);
        }

        $user = $request->user();

        // Check if card already exists
        $existingCard = Card::where('card_number_hash', Card::hashCardNumber($cardNumber))
            ->where('user_id', $user->id)
            ->first();

        if ($existingCard) {
            return response()->json([
                'success' => false,
                'message' => 'Card already exists'
            ], 400);
        }

        // Create new card
        $card = Card::create([
            'user_id' => $user->id,
            'card_number_hash' => Card::hashCardNumber($cardNumber),
            'card_type' => $cardType,
            'expiry_date' => $request->expiry_date,
            'cardholder_name' => $request->cardholder_name,
            'is_active' => true,
            'is_default' => $request->is_default ?? false,
        ]);

        // If this card is set as default, remove default from other cards
        if ($card->is_default) {
            $user->cards()->where('id', '!=', $card->id)->update(['is_default' => false]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Card added successfully',
            'data' => $card
        ]);
    }

    /**
     * Update card details.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'cardholder_name' => 'sometimes|string|max:255',
            'is_active' => 'sometimes|boolean',
            'is_default' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $card = $user->cards()->findOrFail($id);

        // Update card details
        $card->update($request->only(['cardholder_name', 'is_active', 'is_default']));

        // If this card is set as default, remove default from other cards
        if ($card->is_default) {
            $user->cards()->where('id', '!=', $card->id)->update(['is_default' => false]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Card updated successfully',
            'data' => $card->fresh()
        ]);
    }

    /**
     * Delete card.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $card = $user->cards()->findOrFail($id);

        // Don't allow deletion of default card if it's the only card
        if ($card->is_default && $user->cards()->count() === 1) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete the only default card'
            ], 400);
        }

        $card->delete();

        // If deleted card was default, set another card as default
        if ($card->is_default) {
            $newDefaultCard = $user->cards()->first();
            if ($newDefaultCard) {
                $newDefaultCard->update(['is_default' => true]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Card deleted successfully'
        ]);
    }

    /**
     * Validate card details.
     */
    public function validate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'card_number' => 'required|string|min:13|max:19',
            'expiry_date' => 'required|string|regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $cardNumber = preg_replace('/\D/', '', $request->card_number);
        
        // Basic Luhn algorithm validation
        $isValid = $this->validateLuhn($cardNumber);
        
        if (!$isValid) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid card number',
                'data' => [
                    'is_valid' => false,
                    'card_type' => null
                ]
            ], 422);
        }

        // Validate expiry date
        if (!Card::validateExpiryDate($request->expiry_date)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired card',
                'data' => [
                    'is_valid' => false,
                    'card_type' => null
                ]
            ], 422);
        }

        // Detect card type
        $cardType = Card::detectCardType($cardNumber);

        return response()->json([
            'success' => true,
            'message' => 'Card is valid',
            'data' => [
                'is_valid' => true,
                'card_type' => $cardType,
                'card_number_masked' => $this->maskCardNumber($cardNumber)
            ]
        ]);
    }

    /**
     * Set card as default.
     */
    public function setDefault(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $card = $user->cards()->findOrFail($id);

        if (!$card->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot set inactive card as default'
            ], 400);
        }

        $card->setAsDefault();

        return response()->json([
            'success' => true,
            'message' => 'Default card updated successfully',
            'data' => $card->fresh()
        ]);
    }

    /**
     * Get card details.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $card = $user->cards()->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $card
        ]);
    }

    /**
     * Validate card using Luhn algorithm.
     */
    private function validateLuhn(string $cardNumber): bool
    {
        $sum = 0;
        $length = strlen($cardNumber);
        $parity = $length % 2;

        for ($i = $length - 1; $i >= 0; $i--) {
            $digit = intval($cardNumber[$i]);
            
            if ($i % 2 == $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            
            $sum += $digit;
        }

        return $sum % 10 == 0;
    }

    /**
     * Mask card number for display.
     */
    private function maskCardNumber(string $cardNumber): string
    {
        $length = strlen($cardNumber);
        if ($length <= 4) {
            return $cardNumber;
        }

        $masked = str_repeat('*', $length - 4) . substr($cardNumber, -4);
        
        // Add spaces for better readability
        $chunks = str_split($masked, 4);
        return implode(' ', $chunks);
    }
}
