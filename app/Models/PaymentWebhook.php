<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PaymentWebhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_gateway_id',
        'webhook_id',
        'event_type',
        'event_id',
        'payload',
        'headers',
        'signature',
        'status',
        'verification_status',
        'is_verified',
        'verified_at',
        'verification_error',
        'processed_at',
        'processing_error',
        'retry_count',
        'last_retry_at',
        'next_retry_at',
        'processing_result',
        'metadata'
    ];

    protected $casts = [
        'payload' => 'array',
        'headers' => 'array',
        'processing_result' => 'array',
        'metadata' => 'array',
        'verified_at' => 'datetime',
        'processed_at' => 'datetime',
        'last_retry_at' => 'datetime',
        'next_retry_at' => 'datetime',
        'is_verified' => 'boolean'
    ];

    protected $appends = [
        'is_pending',
        'is_processing',
        'is_processed',
        'is_failed',
        'is_ignored',
        'is_verification_pending',
        'is_verification_verified',
        'is_verification_failed',
        'days_since_received',
        'formatted_payload_size'
    ];

    /**
     * Get the payment gateway that owns the webhook.
     */
    public function paymentGateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    /**
     * Check if webhook is pending.
     */
    public function getIsPendingAttribute(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if webhook is processing.
     */
    public function getIsProcessingAttribute(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Check if webhook is processed.
     */
    public function getIsProcessedAttribute(): bool
    {
        return $this->status === 'processed';
    }

    /**
     * Check if webhook is failed.
     */
    public function getIsFailedAttribute(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if webhook is ignored.
     */
    public function getIsIgnoredAttribute(): bool
    {
        return $this->status === 'ignored';
    }

    /**
     * Check if verification is pending.
     */
    public function getIsVerificationPendingAttribute(): bool
    {
        return $this->verification_status === 'pending';
    }

    /**
     * Check if verification is verified.
     */
    public function getIsVerificationVerifiedAttribute(): bool
    {
        return $this->verification_status === 'verified';
    }

    /**
     * Check if verification is failed.
     */
    public function getIsVerificationFailedAttribute(): bool
    {
        return $this->verification_status === 'failed';
    }

    /**
     * Get days since webhook was received.
     */
    public function getDaysSinceReceivedAttribute(): int
    {
        return $this->created_at->diffInDays(now());
    }

    /**
     * Get formatted payload size.
     */
    public function getFormattedPayloadSizeAttribute(): string
    {
        $size = strlen(json_encode($this->payload));
        if ($size < 1024) return $size . ' B';
        if ($size < 1024 * 1024) return round($size / 1024, 2) . ' KB';
        return round($size / (1024 * 1024), 2) . ' MB';
    }

    /**
     * Mark webhook as processing.
     */
    public function markAsProcessing(): void
    {
        $this->update(['status' => 'processing']);
    }

    /**
     * Mark webhook as processed.
     */
    public function markAsProcessed(array $result = []): void
    {
        $this->update([
            'status' => 'processed',
            'processed_at' => now(),
            'processing_result' => $result
        ]);
    }

    /**
     * Mark webhook as failed.
     */
    public function markAsFailed(string $error): void
    {
        $this->update([
            'status' => 'failed',
            'processing_error' => $error
        ]);
    }

    /**
     * Mark webhook as ignored.
     */
    public function markAsIgnored(string $reason = null): void
    {
        $this->update([
            'status' => 'ignored',
            'metadata' => array_merge($this->metadata ?? [], [
                'ignored_reason' => $reason,
                'ignored_at' => now()->toISOString()
            ])
        ]);
    }

    /**
     * Mark verification as pending.
     */
    public function markVerificationPending(): void
    {
        $this->update(['verification_status' => 'pending']);
    }

    /**
     * Mark verification as verified.
     */
    public function markVerificationVerified(): void
    {
        $this->update([
            'verification_status' => 'verified',
            'is_verified' => true,
            'verified_at' => now()
        ]);
    }

    /**
     * Mark verification as failed.
     */
    public function markVerificationFailed(string $error): void
    {
        $this->update([
            'verification_status' => 'failed',
            'verification_error' => $error
        ]);
    }

    /**
     * Mark verification as skipped.
     */
    public function markVerificationSkipped(): void
    {
        $this->update([
            'verification_status' => 'skipped',
            'is_verified' => true
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
     * Set next retry time.
     */
    public function setNextRetryTime(Carbon $nextRetry): void
    {
        $this->update(['next_retry_at' => $nextRetry]);
    }

    /**
     * Check if webhook should be retried.
     */
    public function shouldRetry(): bool
    {
        if ($this->status === 'processed' || $this->status === 'ignored') {
            return false;
        }

        if ($this->retry_count >= 3) {
            return false;
        }

        if ($this->next_retry_at && $this->next_retry_at->isFuture()) {
            return false;
        }

        return true;
    }

    /**
     * Calculate next retry time.
     */
    public function calculateNextRetryTime(): Carbon
    {
        $retryDelays = [5, 15, 60]; // Minutes
        $delayIndex = min($this->retry_count, count($retryDelays) - 1);
        $delayMinutes = $retryDelays[$delayIndex];

        return now()->addMinutes($delayMinutes);
    }

    /**
     * Verify webhook signature.
     */
    public function verifySignature(): bool
    {
        if (!$this->signature) {
            $this->markVerificationSkipped();
            return true;
        }

        try {
            $gateway = $this->paymentGateway;
            $secretKey = $gateway->getConfig('webhook_secret');
            
            if (!$secretKey) {
                $this->markVerificationSkipped();
                return true;
            }

            $expectedSignature = $this->generateSignature($secretKey);
            $isValid = hash_equals($expectedSignature, $this->signature);

            if ($isValid) {
                $this->markVerificationVerified();
            } else {
                $this->markVerificationFailed('Invalid signature');
            }

            return $isValid;

        } catch (\Exception $e) {
            $this->markVerificationFailed('Verification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate webhook signature.
     */
    private function generateSignature(string $secretKey): string
    {
        $payload = json_encode($this->payload);
        return hash_hmac('sha256', $payload, $secretKey);
    }

    /**
     * Process webhook payload.
     */
    public function processPayload(): array
    {
        try {
            $this->markAsProcessing();
            
            $eventType = $this->event_type;
            $payload = $this->payload;
            
            $result = match($eventType) {
                'payment.success' => $this->processPaymentSuccess($payload),
                'payment.failed' => $this->processPaymentFailed($payload),
                'payment.cancelled' => $this->processPaymentCancelled($payload),
                'refund.processed' => $this->processRefundProcessed($payload),
                'refund.failed' => $this->processRefundFailed($payload),
                default => $this->processUnknownEvent($payload)
            };

            $this->markAsProcessed($result);
            return $result;

        } catch (\Exception $e) {
            $error = $e->getMessage();
            $this->markAsFailed($error);
            
            return [
                'success' => false,
                'error' => $error,
                'processed_at' => now()->toISOString()
            ];
        }
    }

    /**
     * Process payment success event.
     */
    private function processPaymentSuccess(array $payload): array
    {
        $transactionId = $payload['transaction_id'] ?? null;
        $amount = $payload['amount'] ?? 0;
        $currency = $payload['currency'] ?? 'SAR';

        // Find and update the corresponding payment transaction
        $transaction = PaymentTransaction::where('gateway_transaction_id', $transactionId)->first();
        
        if ($transaction) {
            $transaction->markAsCompleted();
            $transaction->updateGatewayResponse($payload, ['webhook_processed' => true]);
            
            // Update wallet balance
            $wallet = $transaction->wallet;
            $wallet->addBalance($transaction->net_amount, 'Payment received via ' . $transaction->paymentGateway->name);
            
            return [
                'success' => true,
                'event_type' => 'payment.success',
                'transaction_id' => $transaction->id,
                'wallet_updated' => true,
                'new_balance' => $wallet->balance,
                'processed_at' => now()->toISOString()
            ];
        }

        return [
            'success' => false,
            'error' => 'Transaction not found',
            'event_type' => 'payment.success',
            'transaction_id' => $transactionId
        ];
    }

    /**
     * Process payment failed event.
     */
    private function processPaymentFailed(array $payload): array
    {
        $transactionId = $payload['transaction_id'] ?? null;
        $failureReason = $payload['failure_reason'] ?? 'Unknown error';
        $failureCode = $payload['failure_code'] ?? null;

        $transaction = PaymentTransaction::where('gateway_transaction_id', $transactionId)->first();
        
        if ($transaction) {
            $transaction->markAsFailed($failureReason, $failureCode);
            $transaction->updateGatewayResponse($payload, ['webhook_processed' => true]);
            
            return [
                'success' => true,
                'event_type' => 'payment.failed',
                'transaction_id' => $transaction->id,
                'failure_reason' => $failureReason,
                'failure_code' => $failureCode,
                'processed_at' => now()->toISOString()
            ];
        }

        return [
            'success' => false,
            'error' => 'Transaction not found',
            'event_type' => 'payment.failed',
            'transaction_id' => $transactionId
        ];
    }

    /**
     * Process payment cancelled event.
     */
    private function processPaymentCancelled(array $payload): array
    {
        $transactionId = $payload['transaction_id'] ?? null;
        $cancellationReason = $payload['cancellation_reason'] ?? 'Cancelled by user';

        $transaction = PaymentTransaction::where('gateway_transaction_id', $transactionId)->first();
        
        if ($transaction) {
            $transaction->markAsCancelled();
            $transaction->updateGatewayResponse($payload, ['webhook_processed' => true]);
            
            return [
                'success' => true,
                'event_type' => 'payment.cancelled',
                'transaction_id' => $transaction->id,
                'cancellation_reason' => $cancellationReason,
                'processed_at' => now()->toISOString()
            ];
        }

        return [
            'success' => false,
            'error' => 'Transaction not found',
            'event_type' => 'payment.cancelled',
            'transaction_id' => $transactionId
        ];
    }

    /**
     * Process refund processed event.
     */
    private function processRefundProcessed(array $payload): array
    {
        $transactionId = $payload['transaction_id'] ?? null;
        $refundAmount = $payload['refund_amount'] ?? 0;
        $refundReason = $payload['refund_reason'] ?? 'Refund processed';

        $transaction = PaymentTransaction::where('gateway_transaction_id', $transactionId)->first();
        
        if ($transaction) {
            $transaction->markAsRefunded();
            $transaction->updateGatewayResponse($payload, ['webhook_processed' => true]);
            
            // Update wallet balance (deduct refunded amount)
            $wallet = $transaction->wallet;
            $wallet->deductBalance($refundAmount, 'Refund processed via ' . $transaction->paymentGateway->name);
            
            return [
                'success' => true,
                'event_type' => 'refund.processed',
                'transaction_id' => $transaction->id,
                'refund_amount' => $refundAmount,
                'refund_reason' => $refundReason,
                'wallet_updated' => true,
                'new_balance' => $wallet->balance,
                'processed_at' => now()->toISOString()
            ];
        }

        return [
            'success' => false,
            'error' => 'Transaction not found',
            'event_type' => 'refund.processed',
            'transaction_id' => $transactionId
        ];
    }

    /**
     * Process refund failed event.
     */
    private function processRefundFailed(array $payload): array
    {
        $transactionId = $payload['transaction_id'] ?? null;
        $failureReason = $payload['failure_reason'] ?? 'Refund failed';

        $transaction = PaymentTransaction::where('gateway_transaction_id', $transactionId)->first();
        
        if ($transaction) {
            $transaction->updateGatewayResponse($payload, ['webhook_processed' => true]);
            
            return [
                'success' => true,
                'event_type' => 'refund.failed',
                'transaction_id' => $transaction->id,
                'failure_reason' => $failureReason,
                'processed_at' => now()->toISOString()
            ];
        }

        return [
            'success' => false,
            'error' => 'Transaction not found',
            'event_type' => 'refund.failed',
            'transaction_id' => $transactionId
        ];
    }

    /**
     * Process unknown event.
     */
    private function processUnknownEvent(array $payload): array
    {
        return [
            'success' => true,
            'event_type' => 'unknown',
            'message' => 'Event type not implemented',
            'payload_keys' => array_keys($payload),
            'processed_at' => now()->toISOString()
        ];
    }

    /**
     * Get webhook summary.
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'webhook_id' => $this->webhook_id,
            'event_type' => $this->event_type,
            'event_id' => $this->event_id,
            'status' => $this->status,
            'verification_status' => $this->verification_status,
            'is_verified' => $this->is_verified,
            'retry_count' => $this->retry_count,
            'gateway' => $this->paymentGateway->name,
            'received_at' => $this->created_at->format('Y-m-d H:i:s'),
            'days_since_received' => $this->days_since_received,
            'payload_size' => $this->formatted_payload_size
        ];
    }

    /**
     * Scope for pending webhooks.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for unprocessed webhooks.
     */
    public function scopeUnprocessed($query)
    {
        return $query->whereNotIn('status', ['processed', 'ignored']);
    }

    /**
     * Scope for failed webhooks.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for webhooks by event type.
     */
    public function scopeByEventType($query, $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Scope for webhooks by gateway.
     */
    public function scopeByGateway($query, $gatewayId)
    {
        return $query->where('payment_gateway_id', $gatewayId);
    }

    /**
     * Scope for webhooks ready for retry.
     */
    public function scopeReadyForRetry($query)
    {
        return $query->where('status', 'failed')
                    ->where('retry_count', '<', 3)
                    ->where(function ($q) {
                        $q->whereNull('next_retry_at')
                          ->orWhere('next_retry_at', '<=', now());
                    });
    }

    /**
     * Scope for unverified webhooks.
     */
    public function scopeUnverified($query)
    {
        return $query->where('verification_status', 'pending');
    }

    /**
     * Get webhook statistics.
     */
    public static function getStatistics(): array
    {
        $total = static::count();
        $pending = static::pending()->count();
        $processing = static::where('status', 'processing')->count();
        $processed = static::where('status', 'processed')->count();
        $failed = static::failed()->count();
        $ignored = static::where('status', 'ignored')->count();

        $byEventType = static::selectRaw('event_type, COUNT(*) as count')
            ->groupBy('event_type')
            ->pluck('count', 'event_type')
            ->toArray();

        $byGateway = static::selectRaw('payment_gateway_id, COUNT(*) as count')
            ->groupBy('payment_gateway_id')
            ->pluck('count', 'payment_gateway_id')
            ->toArray();

        $verificationStats = [
            'pending' => static::where('verification_status', 'pending')->count(),
            'verified' => static::where('verification_status', 'verified')->count(),
            'failed' => static::where('verification_status', 'failed')->count(),
            'skipped' => static::where('verification_status', 'skipped')->count()
        ];

        return [
            'total_webhooks' => $total,
            'pending_webhooks' => $pending,
            'processing_webhooks' => $processing,
            'processed_webhooks' => $processed,
            'failed_webhooks' => $failed,
            'ignored_webhooks' => $ignored,
            'webhooks_by_event_type' => $byEventType,
            'webhooks_by_gateway' => $byGateway,
            'verification_stats' => $verificationStats,
            'processing_rate' => $total > 0 ? round((($processed + $ignored) / $total) * 100, 2) : 0
        ];
    }

    /**
     * Clean up old processed webhooks.
     */
    public static function cleanupOldProcessed(): int
    {
        return static::whereIn('status', ['processed', 'ignored'])
            ->where('created_at', '<', now()->subMonths(3))
            ->delete();
    }
}
