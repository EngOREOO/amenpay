<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'card_number_hash',
        'card_type',
        'expiry_date',
        'cardholder_name',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Get the user that owns the card.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if card is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if card is default.
     */
    public function isDefault(): bool
    {
        return $this->is_default;
    }

    /**
     * Check if card is expired.
     */
    public function isExpired(): bool
    {
        $expiry = \DateTime::createFromFormat('m/y', $this->expiry_date);
        return $expiry && $expiry < now();
    }

    /**
     * Set card as default.
     */
    public function setAsDefault(): void
    {
        // Remove default from other cards of the same user
        $this->user->cards()->where('id', '!=', $this->id)->update(['is_default' => false]);
        
        // Set this card as default
        $this->update(['is_default' => true]);
    }

    /**
     * Hash card number for security.
     */
    public static function hashCardNumber(string $cardNumber): string
    {
        return hash('sha256', $cardNumber);
    }

    /**
     * Detect card type from number.
     */
    public static function detectCardType(string $cardNumber): string
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);
        
        if (preg_match('/^4/', $cardNumber)) {
            return 'visa';
        } elseif (preg_match('/^5[1-5]|^2[2-7]|^222[1-9]|^22[3-9]|^2[3-6]|^27[0-1]|^2720/', $cardNumber)) {
            return 'mastercard';
        } elseif (preg_match('/^4[0-9]{6,}$/', $cardNumber)) {
            return 'mada';
        }
        
        return 'unknown';
    }

    /**
     * Validate card expiry date.
     */
    public static function validateExpiryDate(string $expiryDate): bool
    {
        if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expiryDate)) {
            return false;
        }

        $expiry = \DateTime::createFromFormat('m/y', $expiryDate);
        return $expiry && $expiry > now();
    }
}
