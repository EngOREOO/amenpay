<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WalletController extends Controller
{
    /**
     * Get wallet balance.
     */
    public function balance(Request $request): JsonResponse
    {
        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'wallet_number' => $wallet->wallet_number,
                'balance' => $wallet->balance,
                'currency' => $wallet->currency,
                'status' => $wallet->status
            ]
        ]);
    }

    /**
     * Get wallet transactions.
     */
    public function transactions(Request $request): JsonResponse
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
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    /**
     * Transfer money to another wallet.
     */
    public function transfer(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'to_wallet' => 'required|string|exists:wallets,wallet_number',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $fromWallet = $user->wallet;
        $toWallet = Wallet::where('wallet_number', $request->to_wallet)->first();

        if (!$fromWallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        if ($fromWallet->wallet_number === $toWallet->wallet_number) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot transfer to your own wallet'
            ], 400);
        }

        if (!$fromWallet->hasSufficientBalance($request->amount)) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Create outgoing transaction
            $outgoingTransaction = Transaction::create([
                'wallet_id' => $fromWallet->id,
                'type' => 'transfer',
                'amount' => -$request->amount,
                'currency' => $fromWallet->currency,
                'description' => $request->description ?? 'Transfer to ' . $toWallet->wallet_number,
                'status' => 'completed',
                'reference_id' => Transaction::generateReferenceId(),
                'metadata' => [
                    'to_wallet' => $toWallet->wallet_number,
                    'transfer_type' => 'outgoing'
                ]
            ]);

            // Create incoming transaction
            $incomingTransaction = Transaction::create([
                'wallet_id' => $toWallet->id,
                'type' => 'transfer',
                'amount' => $request->amount,
                'currency' => $toWallet->currency,
                'description' => $request->description ?? 'Transfer from ' . $fromWallet->wallet_number,
                'status' => 'completed',
                'reference_id' => Transaction::generateReferenceId(),
                'metadata' => [
                    'from_wallet' => $fromWallet->wallet_number,
                    'transfer_type' => 'incoming'
                ]
            ]);

            // Update balances
            $fromWallet->subtractBalance($request->amount);
            $toWallet->addBalance($request->amount);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transfer completed successfully',
                'data' => [
                    'transaction_id' => $outgoingTransaction->id,
                    'reference_id' => $outgoingTransaction->reference_id,
                    'amount' => $request->amount,
                    'to_wallet' => $toWallet->wallet_number,
                    'new_balance' => $fromWallet->fresh()->balance
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Transfer failed'
            ], 500);
        }
    }

    /**
     * Get wallet analytics.
     */
    public function analytics(Request $request): JsonResponse
    {
        $user = $request->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found'
            ], 404);
        }

        $period = $request->get('period', 'month'); // week, month, year
        $startDate = $this->getStartDate($period);

        $transactions = $wallet->transactions()
            ->where('created_at', '>=', $startDate)
            ->get();

        $analytics = [
            'total_income' => $transactions->where('amount', '>', 0)->sum('amount'),
            'total_expenses' => abs($transactions->where('amount', '<', 0)->sum('amount')),
            'net_amount' => $transactions->sum('amount'),
            'transaction_count' => $transactions->count(),
            'top_categories' => $this->getTopCategories($transactions),
            'daily_spending' => $this->getDailySpending($transactions, $period),
            'transaction_types' => $this->getTransactionTypes($transactions)
        ];

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Get wallet statement.
     */
    public function statement(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
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

        $transactions = $wallet->transactions()
            ->with(['category'])
            ->whereBetween('created_at', [$request->start_date, $request->end_date])
            ->orderBy('created_at', 'desc')
            ->get();

        $statement = [
            'wallet_number' => $wallet->wallet_number,
            'period' => [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date
            ],
            'opening_balance' => $this->calculateOpeningBalance($wallet, $request->start_date),
            'closing_balance' => $wallet->balance,
            'total_income' => $transactions->where('amount', '>', 0)->sum('amount'),
            'total_expenses' => abs($transactions->where('amount', '<', 0)->sum('amount')),
            'transactions' => $transactions
        ];

        return response()->json([
            'success' => true,
            'data' => $statement
        ]);
    }

    /**
     * Deposit money to wallet.
     */
    public function deposit(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:card,bank_transfer,cash',
            'description' => 'nullable|string|max:255',
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

        // Create deposit transaction
        $transaction = Transaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'deposit',
            'amount' => $request->amount,
            'currency' => $wallet->currency,
            'description' => $request->description ?? 'Wallet deposit',
            'status' => 'completed',
            'reference_id' => Transaction::generateReferenceId(),
            'metadata' => [
                'payment_method' => $request->payment_method
            ]
        ]);

        // Update wallet balance
        $wallet->addBalance($request->amount);

        return response()->json([
            'success' => true,
            'message' => 'Deposit completed successfully',
            'data' => [
                'transaction_id' => $transaction->id,
                'reference_id' => $transaction->reference_id,
                'amount' => $request->amount,
                'new_balance' => $wallet->fresh()->balance
            ]
        ]);
    }

    /**
     * Withdraw money from wallet.
     */
    public function withdrawal(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'withdrawal_method' => 'required|in:bank_transfer,cash_pickup',
            'bank_details' => 'required_if:withdrawal_method,bank_transfer|array',
            'description' => 'nullable|string|max:255',
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

        if (!$wallet->hasSufficientBalance($request->amount)) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance'
            ], 400);
        }

        // Create withdrawal transaction
        $transaction = Transaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'withdrawal',
            'amount' => -$request->amount,
            'currency' => $wallet->currency,
            'description' => $request->description ?? 'Wallet withdrawal',
            'status' => 'pending', // Will be updated when processed
            'reference_id' => Transaction::generateReferenceId(),
            'metadata' => [
                'withdrawal_method' => $request->withdrawal_method,
                'bank_details' => $request->bank_details ?? null
            ]
        ]);

        // Update wallet balance
        $wallet->subtractBalance($request->amount);

        return response()->json([
            'success' => true,
            'message' => 'Withdrawal request submitted successfully',
            'data' => [
                'transaction_id' => $transaction->id,
                'reference_id' => $transaction->reference_id,
                'amount' => $request->amount,
                'status' => $transaction->status,
                'new_balance' => $wallet->fresh()->balance
            ]
        ]);
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
    private function getTopCategories($transactions): array
    {
        return $transactions->where('amount', '<', 0)
            ->groupBy('category_id')
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
    private function getDailySpending($transactions, string $period): array
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
                'amount' => abs($dayTransactions->where('amount', '<', 0)->sum('amount')),
                'transaction_count' => $dayTransactions->count()
            ];
        }

        return $dailyData;
    }

    /**
     * Helper method to get transaction types breakdown.
     */
    private function getTransactionTypes($transactions): array
    {
        return $transactions->groupBy('type')
            ->map(function ($group) {
                return [
                    'type' => $group->first()->type,
                    'count' => $group->count(),
                    'total_amount' => $group->sum('amount')
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Helper method to calculate opening balance.
     */
    private function calculateOpeningBalance(Wallet $wallet, string $startDate): float
    {
        $transactionsBeforeStart = $wallet->transactions()
            ->where('created_at', '<', $startDate)
            ->sum('amount');

        return $wallet->balance - $transactionsBeforeStart;
    }
}
