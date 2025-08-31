<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'payment_gateway_id',
        'gateway_transaction_id',
        'reference_id',
        'type',
        'status',
        'amount',
        'fee_amount',
        'net_amount',
        'currency',
        'payment_method',
        'card_last_four',
        'card_brand',
        'payment_details',
        'gateway_response',
        'gateway_metadata',
        'processed_at',
        'completed_at',
        'failed_at',
        'failure_reason',
        'failure_code',
        'reconciliation_data',
        'is_reconciled',
        'reconciled_at',
        'reconciled_by',
        'webhook_data',
        'webhook_processed',
        'webhook_processed_at',
        'retry_count',
        'last_retry_at',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'payment_details' => 'array',
        'gateway_response' => 'array',
        'gateway_metadata' => 'array',
        'reconciliation_data' => 'array',
        'webhook_data' => 'array',
        'metadata' => 'array',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime',
        'reconciled_at' => 'datetime',
        'webhook_processed_at' => 'datetime',
        'last_retry_at' => 'datetime',
        'is_reconciled' => 'boolean',
        'webhook_processed' => 'boolean'
    ];

    protected $appends = [
        'is_pending',
        'is_processing',
        'is_completed',
        'is_failed',
        'is_refundable',
        'days_since_created',
        'formatted_amount',
        'formatted_fee_amount',
        'formatted_net_amount'
    ];

    /**
     * Get the user that owns the payment transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wallet associated with the payment transaction.
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Get the payment gateway associated with the payment transaction.
     */
    public function paymentGateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    /**
     * Check if transaction is pending.
     */
    public function getIsPendingAttribute(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transaction is processing.
     */
    public function getIsProcessingAttribute(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Check if transaction is completed.
     */
    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if transaction is failed.
     */
    public function getIsFailedAttribute(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if transaction is refundable.
     */
    public function getIsRefundableAttribute(): bool
    {
        if (!$this->is_completed) return false;
        if (!$this->paymentGateway->supportsRefunds()) return false;
        
        $refundLimit = $this->paymentGateway->refund_days_limit;
        $daysSinceCompletion = $this->completed_at ? $this->completed_at->diffInDays(now()) : 0;
        
        return $daysSinceCompletion <= $refundLimit;
    }

    /**
     * Get days since transaction was created.
     */
    public function getDaysSinceCreatedAttribute(): int
    {
        return $this->created_at->diffInDays(now());
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'SAR ' . number_format($this->amount, 2);
    }

    /**
     * Get formatted fee amount.
     */
    public function getFormattedFeeAmountAttribute(): string
    {
        return 'SAR ' . number_format($this->fee_amount, 2);
    }

    /**
     * Get formatted net amount.
     */
    public function getFormattedNetAmountAttribute(): string
    {
        return 'SAR ' . number_format($this->net_amount, 2);
    }

    /**
     * Mark transaction as processing.
     */
    public function markAsProcessing(): void
    {
        $this->update([
            'status' => 'processing',
            'processed_at' => now()
        ]);
    }

    /**
     * Mark transaction as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
    }

    /**
     * Mark transaction as failed.
     */
    public function markAsFailed(string $reason, string $code = null): void
    {
        $this->update([
            'status' => 'failed',
            'failed_at' => now(),
            'failure_reason' => $reason,
            'failure_code' => $code
        ]);
    }

    /**
     * Mark transaction as cancelled.
     */
    public function markAsCancelled(): void
    {
        $this->update([
            'status' => 'cancelled'
        ]);
    }

    /**
     * Mark transaction as refunded.
     */
    public function markAsRefunded(): void
    {
        $this->update([
            'status' => 'refunded'
        ]);
    }

    /**
     * Mark transaction as partially refunded.
     */
    public function markAsPartiallyRefunded(): void
    {
        $this->update([
            'status' => 'partially_refunded'
        ]);
    }

    /**
     * Update gateway response.
     */
    public function updateGatewayResponse(array $response, array $metadata = []): void
    {
        $this->update([
            'gateway_response' => $response,
            'gateway_metadata' => array_merge($this->gateway_metadata ?? [], $metadata)
        ]);
    }

    /**
     * Update webhook data.
     */
    public function updateWebhookData(array $data): void
    {
        $this->update([
            'webhook_data' => $data,
            'webhook_processed' => true,
            'webhook_processed_at' => now()
        ]);
    }

    /**
     * Mark webhook as processed.
     */
    public function markWebhookProcessed(): void
    {
        $this->update([
            'webhook_processed' => true,
            'webhook_processed_at' => now()
        ]);
    }

    /**
     * Increment retry count.
     */
    public function incrementRetryCount(): void
    {
        $this->increment('retry_count');
        $this->update(['last_retry_at' => now()]);
    }

    /**
     * Mark transaction as reconciled.
     */
    public function markAsReconciled(string $reconciledBy, array $reconciliationData = []): void
    {
        $this->update([
            'is_reconciled' => true,
            'reconciled_at' => now(),
            'reconciled_by' => $reconciledBy,
            'reconciliation_data' => array_merge($this->reconciliation_data ?? [], $reconciliationData)
        ]);
    }

    /**
     * Get transaction summary.
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'reference_id' => $this->reference_id,
            'gateway_transaction_id' => $this->gateway_transaction_id,
            'type' => $this->type,
            'status' => $this->status,
            'amount' => $this->formatted_amount,
            'fee_amount' => $this->formatted_fee_amount,
            'net_amount' => $this->formatted_net_amount,
            'currency' => $this->currency,
            'payment_method' => $this->payment_method,
            'card_info' => $this->card_brand && $this->card_last_four ? 
                "{$this->card_brand} **** {$this->card_last_four}" : null,
            'gateway' => $this->paymentGateway->name,
            'is_refundable' => $this->is_refundable,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'days_since_created' => $this->days_since_created
        ];
    }

    /**
     * Get detailed transaction info.
     */
    public function getDetailedInfo(): array
    {
        return array_merge($this->getSummary(), [
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'phone' => $this->user->phone
            ],
            'wallet' => [
                'id' => $this->wallet->id,
                'wallet_id' => $this->wallet->wallet_id
            ],
            'gateway_details' => $this->paymentGateway->getSummary(),
            'timestamps' => [
                'created_at' => $this->created_at->toISOString(),
                'processed_at' => $this->processed_at?->toISOString(),
                'completed_at' => $this->completed_at?->toISOString(),
                'failed_at' => $this->failed_at?->toISOString()
            ],
            'webhook_status' => [
                'processed' => $this->webhook_processed,
                'processed_at' => $this->webhook_processed_at?->toISOString(),
                'retry_count' => $this->retry_count,
                'last_retry_at' => $this->last_retry_at?->toISOString()
            ],
            'reconciliation_status' => [
                'is_reconciled' => $this->is_reconciled,
                'reconciled_at' => $this->reconciled_at?->toISOString(),
                'reconciled_by' => $this->reconciled_by
            ]
        ]);
    }

    /**
     * Scope for pending transactions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for processing transactions.
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    /**
     * Scope for completed transactions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for failed transactions.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for refundable transactions.
     */
    public function scopeRefundable($query)
    {
        return $query->where('status', 'completed')
                    ->whereHas('paymentGateway', function ($q) {
                        $q->where('supports_refunds', true);
                    });
    }

    /**
     * Scope for transactions by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for transactions by payment method.
     */
    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope for transactions by gateway.
     */
    public function scopeByGateway($query, $gatewayId)
    {
        return $query->where('payment_gateway_id', $gatewayId);
    }

    /**
     * Scope for transactions by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for unreconciled transactions.
     */
    public function scopeUnreconciled($query)
    {
        return $query->where('is_reconciled', false);
    }

    /**
     * Scope for unprocessed webhooks.
     */
    public function scopeUnprocessedWebhooks($query)
    {
        return $query->where('webhook_processed', false);
    }

    /**
     * Scope for transactions by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for transactions by amount range.
     */
    public function scopeByAmountRange($query, $minAmount, $maxAmount)
    {
        return $query->whereBetween('amount', [$minAmount, $maxAmount]);
    }

    /**
     * Get transaction statistics.
     */
    public static function getStatistics(): array
    {
        $total = static::count();
        $pending = static::pending()->count();
        $processing = static::processing()->count();
        $completed = static::completed()->count();
        $failed = static::failed()->count();

        $totalAmount = static::completed()->sum('amount');
        $totalFees = static::completed()->sum('fee_amount');
        $averageAmount = static::completed()->avg('amount');

        $byType = static::selectRaw('type, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('type')
            ->get()
            ->keyBy('type')
            ->map(function ($item) {
                return [
                    'count' => $item->count,
                    'total_amount' => round($item->total_amount, 2)
                ];
            })
            ->toArray();

        $byPaymentMethod = static::selectRaw('payment_method, COUNT(*) as count')
            ->whereNotNull('payment_method')
            ->groupBy('payment_method')
            ->pluck('count', 'payment_method')
            ->toArray();

        return [
            'total_transactions' => $total,
            'pending_transactions' => $pending,
            'processing_transactions' => $processing,
            'completed_transactions' => $completed,
            'failed_transactions' => $failed,
            'total_amount' => round($totalAmount, 2),
            'total_fees' => round($totalFees, 2),
            'average_amount' => round($averageAmount, 2),
            'transactions_by_type' => $byType,
            'transactions_by_payment_method' => $byPaymentMethod,
            'success_rate' => $total > 0 ? round(($completed / $total) * 100, 2) : 0
        ];
    }

    /**
     * Generate unique reference ID.
     */
    public static function generateReferenceId(): string
    {
        do {
            $referenceId = 'TXN' . date('Ymd') . strtoupper(uniqid());
        } while (static::where('reference_id', $referenceId)->exists());

        return $referenceId;
    }

    /**
     * Create payment transaction.
     */
    public static function createPayment(
        $userId,
        $walletId,
        $paymentGatewayId,
        float $amount,
        string $paymentMethod,
        array $paymentDetails = [],
        array $metadata = []
    ): self {
        $gateway = PaymentGateway::findOrFail($paymentGatewayId);
        $fees = $gateway->calculateFees($amount);
        $amountValidation = $gateway->validateAmount($amount);

        if (!$amountValidation['is_valid']) {
            throw new \InvalidArgumentException(implode(', ', $amountValidation['errors']));
        }

        return static::create([
            'user_id' => $userId,
            'wallet_id' => $walletId,
            'payment_gateway_id' => $paymentGatewayId,
            'reference_id' => static::generateReferenceId(),
            'type' => 'payment',
            'status' => 'pending',
            'amount' => $amount,
            'fee_amount' => $fees['total_fee'],
            'net_amount' => $fees['net_amount'],
            'currency' => 'SAR',
            'payment_method' => $paymentMethod,
            'payment_details' => $paymentDetails,
            'metadata' => $metadata
        ]);
    }

    /**
     * Clean up old failed transactions.
     */
    public static function cleanupOldFailed(): int
    {
        return static::where('status', 'failed')
            ->where('created_at', '<', now()->subMonths(3))
            ->delete();
    }
}
