<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Get user transactions.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        $transactions = $wallet->transactions()
            ->with(['category'])
            ->when($request->type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->category_id, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($request->start_date, function ($query, $startDate) {
                return $query->where('created_at', '>=', $startDate);
            })
            ->when($request->end_date, function ($query, $endDate) {
                return $query->where('created_at', '<=', $endDate);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    /**
     * Get transaction details.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        $transaction = $wallet->transactions()
            ->with(['category'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }

    /**
     * Process a transaction.
     */
    public function process(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:payment,transfer,deposit,withdrawal',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:255',
            'metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        // Check if user has sufficient balance for outgoing transactions
        if (in_array($request->type, ['payment', 'transfer', 'withdrawal']) && 
            !$wallet->hasSufficientBalance($request->amount)) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance'
            ], 400);
        }

        // Determine transaction amount (negative for outgoing, positive for incoming)
        $amount = in_array($request->type, ['payment', 'transfer', 'withdrawal']) 
            ? -$request->amount 
            : $request->amount;

        // Create transaction
        $transaction = Transaction::create([
            'wallet_id' => $wallet->id,
            'type' => $request->type,
            'amount' => $amount,
            'currency' => $wallet->currency,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'status' => 'completed',
            'reference_id' => Transaction::generateReferenceId(),
            'metadata' => $request->metadata ?? []
        ]);

        // Update wallet balance
        if ($amount > 0) {
            $wallet->addBalance($amount);
        } else {
            $wallet->subtractBalance(abs($amount));
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction processed successfully',
            'data' => [
                'transaction_id' => $transaction->id,
                'reference_id' => $transaction->reference_id,
                'amount' => abs($request->amount),
                'type' => $transaction->type,
                'status' => $transaction->status,
                'new_balance' => $wallet->fresh()->balance
            ]
        ]);
    }

    /**
     * Process payment.
     */
    public function processPayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:255',
            'payment_method' => 'required|in:wallet,card',
            'card_id' => 'required_if:payment_method,card|exists:cards,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        // Validate card if payment method is card
        if ($request->payment_method === 'card') {
            $card = $user->cards()->findOrFail($request->card_id);
            if (!$card->isActive() || $card->isExpired()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired card'
                ], 400);
            }
        }

        // Check wallet balance if payment method is wallet
        if ($request->payment_method === 'wallet' && !$wallet->hasSufficientBalance($request->amount)) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance'
            ], 400);
        }

        // Create payment transaction
        $transaction = Transaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'payment',
            'amount' => -$request->amount,
            'currency' => $wallet->currency,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'status' => 'completed',
            'reference_id' => Transaction::generateReferenceId(),
            'metadata' => [
                'payment_method' => $request->payment_method,
                'card_id' => $request->card_id ?? null
            ]
        ]);

        // Update wallet balance if payment method is wallet
        if ($request->payment_method === 'wallet') {
            $wallet->subtractBalance($request->amount);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment processed successfully',
            'data' => [
                'transaction_id' => $transaction->id,
                'reference_id' => $transaction->reference_id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'new_balance' => $wallet->fresh()->balance
            ]
        ]);
    }

    /**
     * Get payment categories.
     */
    public function categories(Request $request): JsonResponse
    {
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Process bill payment.
     */
    public function billPayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'bill_type' => 'required|in:electricity,water,gas,internet,phone',
            'account_number' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:wallet,card',
            'card_id' => 'required_if:payment_method,card|exists:cards,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        // Get bill category based on bill type
        $billCategories = [
            'electricity' => 20, // Electricity category ID
            'water' => 21,       // Water category ID
            'gas' => 22,         // Gas category ID
            'internet' => 23,    // Internet category ID
            'phone' => 24,       // Phone category ID
        ];

        $categoryId = $billCategories[$request->bill_type] ?? null;

        // Create bill payment transaction
        $transaction = Transaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'payment',
            'amount' => -$request->amount,
            'currency' => $wallet->currency,
            'category_id' => $categoryId,
            'description' => ucfirst($request->bill_type) . ' bill payment - ' . $request->account_number,
            'status' => 'completed',
            'reference_id' => Transaction::generateReferenceId(),
            'metadata' => [
                'bill_type' => $request->bill_type,
                'account_number' => $request->account_number,
                'payment_method' => $request->payment_method,
                'card_id' => $request->card_id ?? null
            ]
        ]);

        // Update wallet balance if payment method is wallet
        if ($request->payment_method === 'wallet') {
            $wallet->subtractBalance($request->amount);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bill payment processed successfully',
            'data' => [
                'transaction_id' => $transaction->id,
                'reference_id' => $transaction->reference_id,
                'bill_type' => $request->bill_type,
                'account_number' => $request->account_number,
                'amount' => $request->amount,
                'new_balance' => $wallet->fresh()->balance
            ]
        ]);
    }

    /**
     * Process QR payment.
     */
    public function qrPayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'qr_code' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:wallet,card',
            'card_id' => 'required_if:payment_method,card|exists:cards,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        // In a real application, you would validate the QR code
        // and extract merchant information from it
        $qrData = $this->parseQRCode($request->qr_code);

        // Create QR payment transaction
        $transaction = Transaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'payment',
            'amount' => -$request->amount,
            'currency' => $wallet->currency,
            'description' => 'QR Payment - ' . ($qrData['merchant_name'] ?? 'Unknown Merchant'),
            'status' => 'completed',
            'reference_id' => Transaction::generateReferenceId(),
            'metadata' => [
                'qr_code' => $request->qr_code,
                'merchant_info' => $qrData,
                'payment_method' => $request->payment_method,
                'card_id' => $request->card_id ?? null
            ]
        ]);

        // Update wallet balance if payment method is wallet
        if ($request->payment_method === 'wallet') {
            $wallet->subtractBalance($request->amount);
        }

        return response()->json([
            'success' => true,
            'message' => 'QR payment processed successfully',
            'data' => [
                'transaction_id' => $transaction->id,
                'reference_id' => $transaction->reference_id,
                'merchant_name' => $qrData['merchant_name'] ?? 'Unknown Merchant',
                'amount' => $request->amount,
                'new_balance' => $wallet->fresh()->balance
            ]
        ]);
    }

    /**
     * Get payment history.
     */
    public function paymentHistory(Request $request): JsonResponse
    {
        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        $payments = $wallet->transactions()
            ->where('type', 'payment')
            ->with(['category'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    /**
     * Process refund.
     */
    public function refund(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|exists:transactions,id',
            'refund_amount' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        $originalTransaction = Transaction::findOrFail($request->transaction_id);

        // Check if transaction belongs to user's wallet
        if ($originalTransaction->wallet_id !== $wallet->id) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        // Check if refund amount is valid
        if ($request->refund_amount > abs($originalTransaction->amount)) {
            return response()->json([
                'success' => false,
                'message' => 'Refund amount cannot exceed original transaction amount'
            ], 400);
        }

        // Create refund transaction
        $refundTransaction = Transaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'refund',
            'amount' => $request->refund_amount,
            'currency' => $wallet->currency,
            'description' => 'Refund for transaction #' . $originalTransaction->reference_id . ' - ' . $request->reason,
            'status' => 'completed',
            'reference_id' => Transaction::generateReferenceId(),
            'metadata' => [
                'original_transaction_id' => $originalTransaction->id,
                'original_reference_id' => $originalTransaction->reference_id,
                'refund_reason' => $request->reason
            ]
        ]);

        // Update wallet balance
        $wallet->addBalance($request->refund_amount);

        return response()->json([
            'success' => true,
            'message' => 'Refund processed successfully',
            'data' => [
                'refund_id' => $refundTransaction->id,
                'refund_reference_id' => $refundTransaction->reference_id,
                'original_transaction_id' => $originalTransaction->id,
                'refund_amount' => $request->refund_amount,
                'new_balance' => $wallet->fresh()->balance
            ]
        ]);
    }

    /**
     * Get transaction status.
     */
    public function status(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        $transaction = $wallet->transactions()->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'transaction_id' => $transaction->id,
                'reference_id' => $transaction->reference_id,
                'status' => $transaction->status,
                'type' => $transaction->type,
                'amount' => abs($transaction->amount),
                'created_at' => $transaction->created_at,
                'updated_at' => $transaction->updated_at
            ]
        ]);
    }

    /**
     * Get spending analytics.
     */
    public function spendingAnalytics(Request $request): JsonResponse
    {
        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);

        $transactions = $wallet->transactions()
            ->where('amount', '<', 0)
            ->where('created_at', '>=', $startDate)
            ->get();

        $analytics = [
            'total_spending' => abs($transactions->sum('amount')),
            'transaction_count' => $transactions->count(),
            'average_transaction' => $transactions->count() > 0 ? abs($transactions->avg('amount')) : 0,
            'top_categories' => $this->getTopSpendingCategories($transactions),
            'daily_spending' => $this->getDailySpendingData($transactions, $period),
            'spending_trend' => $this->getSpendingTrend($transactions)
        ];

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Get income analytics.
     */
    public function incomeAnalytics(Request $request): JsonResponse
    {
        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);

        $transactions = $wallet->transactions()
            ->where('amount', '>', 0)
            ->where('created_at', '>=', $startDate)
            ->get();

        $analytics = [
            'total_income' => $transactions->sum('amount'),
            'transaction_count' => $transactions->count(),
            'average_transaction' => $transactions->count() > 0 ? $transactions->avg('amount') : 0,
            'income_sources' => $this->getIncomeSources($transactions),
            'daily_income' => $this->getDailyIncomeData($transactions, $period)
        ];

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Get category analytics.
     */
    public function categoryAnalytics(Request $request): JsonResponse
    {
        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);

        $transactions = $wallet->transactions()
            ->with(['category'])
            ->where('created_at', '>=', $startDate)
            ->get();

        $categoryAnalytics = $transactions->groupBy('category_id')
            ->map(function ($group) {
                $category = $group->first()->category;
                return [
                    'category_id' => $category?->id,
                    'category_name_ar' => $category?->name_ar ?? 'غير مصنف',
                    'category_name_en' => $category?->name_en ?? 'Uncategorized',
                    'total_amount' => $group->sum('amount'),
                    'transaction_count' => $group->count(),
                    'average_amount' => $group->avg('amount')
                ];
            })
            ->sortByDesc('total_amount')
            ->values();

        return response()->json([
            'success' => true,
            'data' => $categoryAnalytics
        ]);
    }

    /**
     * Helper method to parse QR code.
     */
    private function parseQRCode(string $qrCode): array
    {
        // In a real application, you would decode the QR code
        // and extract merchant information
        return [
            'merchant_name' => 'Sample Merchant',
            'merchant_id' => 'MERCH001',
            'location' => 'Riyadh, Saudi Arabia'
        ];
    }

    /**
     * Helper method to get start date based on period.
     */
    private function getStartDate(string $period): Carbon
    {
        return match($period) {
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subMonth(),
        };
    }

    /**
     * Helper method to get top spending categories.
     */
    private function getTopSpendingCategories($transactions): array
    {
        return $transactions->groupBy('category_id')
            ->map(function ($group) {
                return [
                    'category_id' => $group->first()->category_id,
                    'category_name' => $group->first()->category?->name_ar ?? 'غير مصنف',
                    'total_amount' => abs($group->sum('amount')),
                    'transaction_count' => $group->count()
                ];
            })
            ->sortByDesc('total_amount')
            ->take(5)
            ->values()
            ->toArray();
    }

    /**
     * Helper method to get daily spending data.
     */
    private function getDailySpendingData($transactions, string $period): array
    {
        $days = match($period) {
            'week' => 7,
            'month' => 30,
            'year' => 365,
            default => 30,
        };

        $dailyData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dayTransactions = $transactions->filter(function ($transaction) use ($date) {
                return $transaction->created_at->format('Y-m-d') === $date;
            });

            $dailyData[] = [
                'date' => $date,
                'amount' => abs($dayTransactions->sum('amount')),
                'transaction_count' => $dayTransactions->count()
            ];
        }

        return $dailyData;
    }

    /**
     * Helper method to get spending trend.
     */
    private function getSpendingTrend($transactions): array
    {
        // Calculate spending trend over time
        $weeklyData = [];
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekEnd = Carbon::now()->subWeeks($i)->endOfWeek();
            
            $weekTransactions = $transactions->filter(function ($transaction) use ($weekStart, $weekEnd) {
                return $transaction->created_at->between($weekStart, $weekEnd);
            });

            $weeklyData[] = [
                'week' => $weekStart->format('Y-m-d'),
                'amount' => abs($weekTransactions->sum('amount')),
                'transaction_count' => $weekTransactions->count()
            ];
        }

        return $weeklyData;
    }

    /**
     * Helper method to get income sources.
     */
    private function getIncomeSources($transactions): array
    {
        return $transactions->groupBy('type')
            ->map(function ($group) {
                return [
                    'source' => $group->first()->type,
                    'total_amount' => $group->sum('amount'),
                    'transaction_count' => $group->count()
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Helper method to get daily income data.
     */
    private function getDailyIncomeData($transactions, string $period): array
    {
        $days = match($period) {
            'week' => 7,
            'month' => 30,
            'year' => 365,
            default => 30,
        };

        $dailyData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dayTransactions = $transactions->filter(function ($transaction) use ($date) {
                return $transaction->created_at->format('Y-m-d') === $date;
            });

            $dailyData[] = [
                'date' => $date,
                'amount' => $dayTransactions->sum('amount'),
                'transaction_count' => $dayTransactions->count()
            ];
        }

        return $dailyData;
    }

    /**
     * Get transaction reports.
     */
    public function reports(Request $request): JsonResponse
    {
        $user = $request->user();
        $wallet = $user->wallet;
        
        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'User has no wallet'
            ], 404);
        }

        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        // Get transaction summary
        $summary = [
            'total_transactions' => $wallet->transactions()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'total_income' => $wallet->transactions()
                ->where('amount', '>', 0)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount'),
            'total_expenses' => abs($wallet->transactions()
                ->where('amount', '<', 0)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount')),
            'net_amount' => $wallet->transactions()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount')
        ];

        // Get transactions by category
        $byCategory = $wallet->transactions()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map(function ($transactions) {
                return [
                    'count' => $transactions->count(),
                    'total_amount' => $transactions->sum('amount'),
                    'transactions' => $transactions
                ];
            });

        // Get transactions by type
        $byType = $wallet->transactions()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy('type')
            ->map(function ($transactions) {
                return [
                    'count' => $transactions->count(),
                    'total_amount' => $transactions->sum('amount')
                ];
            });

        // Get daily spending trends
        $dailyTrends = $wallet->transactions()
            ->where('amount', '<', 0)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($transaction) {
                return $transaction->created_at->format('Y-m-d');
            })
            ->map(function ($transactions) {
                return abs($transactions->sum('amount'));
            });

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => $summary,
                'by_category' => $byCategory,
                'by_type' => $byType,
                'daily_trends' => $dailyTrends,
                'period' => [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d')
                ]
            ]
        ]);
    }
}
