<?php

namespace App\Jobs;

use App\Models\PaymentTransaction;
use App\Services\PaymentGatewayService;
use App\Services\CommunicationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 300; // 5 minutes
    public $maxExceptions = 3;

    protected $transactionId;
    protected $gatewayType;
    protected $retryCount = 0;

    /**
     * Create a new job instance.
     */
    public function __construct(int $transactionId, string $gatewayType = 'mada')
    {
        $this->transactionId = $transactionId;
        $this->gatewayType = $gatewayType;
        $this->onQueue('payments');
    }

    /**
     * Execute the job.
     */
    public function handle(PaymentGatewayService $paymentService, CommunicationService $communicationService): void
    {
        try {
            Log::info('Processing payment job started', [
                'transaction_id' => $this->transactionId,
                'gateway_type' => $this->gatewayType,
                'attempt' => $this->attempts()
            ]);

            // Get the payment transaction
            $transaction = PaymentTransaction::findOrFail($this->transactionId);
            
            if ($transaction->status !== 'pending') {
                Log::info('Payment transaction already processed', [
                    'transaction_id' => $this->transactionId,
                    'status' => $transaction->status
                ]);
                return;
            }

            // Process payment through gateway
            $result = $paymentService->processPayment($transaction, $this->gatewayType);

            if ($result['success']) {
                // Update transaction status
                $transaction->update([
                    'status' => 'processing',
                    'processed_at' => now(),
                    'metadata' => array_merge($transaction->metadata ?? [], [
                        'job_processed_at' => now()->toISOString(),
                        'gateway_response' => $result
                    ])
                ]);

                Log::info('Payment processed successfully', [
                    'transaction_id' => $this->transactionId,
                    'result' => $result
                ]);

                // Send notification to user
                $this->sendPaymentNotification($transaction, $result, $communicationService);

            } else {
                // Mark transaction as failed
                $transaction->update([
                    'status' => 'failed',
                    'failed_at' => now(),
                    'metadata' => array_merge($transaction->metadata ?? [], [
                        'failure_reason' => $result['message'],
                        'job_processed_at' => now()->toISOString()
                    ])
                ]);

                Log::error('Payment processing failed', [
                    'transaction_id' => $this->transactionId,
                    'error' => $result['message']
                ]);

                // Send failure notification
                $this->sendFailureNotification($transaction, $result, $communicationService);
            }

        } catch (\Exception $e) {
            Log::error('Payment job failed with exception', [
                'transaction_id' => $this->transactionId,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts()
            ]);

            // Update transaction status
            if (isset($transaction)) {
                $transaction->update([
                    'status' => 'failed',
                    'failed_at' => now(),
                    'metadata' => array_merge($transaction->metadata ?? [], [
                        'failure_reason' => $e->getMessage(),
                        'job_failed_at' => now()->toISOString()
                    ])
                ]);
            }

            // Re-throw exception for retry mechanism
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Payment job failed permanently', [
            'transaction_id' => $this->transactionId,
            'gateway_type' => $this->gatewayType,
            'error' => $exception->getMessage()
        ]);

        // Update transaction status to failed
        try {
            $transaction = PaymentTransaction::find($this->transactionId);
            if ($transaction) {
                $transaction->update([
                    'status' => 'failed',
                    'failed_at' => now(),
                    'metadata' => array_merge($transaction->metadata ?? [], [
                        'failure_reason' => 'Job failed permanently: ' . $exception->getMessage(),
                        'job_failed_permanently_at' => now()->toISOString()
                    ])
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to update transaction status after job failure', [
                'transaction_id' => $this->transactionId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send payment notification to user
     */
    protected function sendPaymentNotification(PaymentTransaction $transaction, array $result, CommunicationService $communicationService): void
    {
        try {
            $user = $transaction->user;
            
            // Send SMS notification
            $message = $user->language === 'ar' 
                ? "تم استلام الدفع بنجاح. المبلغ: {$transaction->amount} ريال. رقم العملية: {$transaction->reference_id}"
                : "Payment received successfully. Amount: {$transaction->amount} SAR. Transaction ID: {$transaction->reference_id}";
            
            $communicationService->sendSms($user->phone, $message);

            // Send push notification
            $title = $user->language === 'ar' ? 'تم استلام الدفع' : 'Payment Received';
            $communicationService->sendPushNotification($user, $title, $message, [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'type' => 'payment_success'
            ]);

            // Send email notification
            $communicationService->sendTransactionEmail($user, [
                'type' => 'payment',
                'amount' => $transaction->amount,
                'currency' => 'SAR',
                'reference_id' => $transaction->reference_id,
                'gateway' => $this->gatewayType
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send payment notification', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send failure notification to user
     */
    protected function sendFailureNotification(PaymentTransaction $transaction, array $result, CommunicationService $communicationService): void
    {
        try {
            $user = $transaction->user;
            
            // Send SMS notification
            $message = $user->language === 'ar' 
                ? "فشل في معالجة الدفع. المبلغ: {$transaction->amount} ريال. يرجى المحاولة مرة أخرى."
                : "Payment processing failed. Amount: {$transaction->amount} SAR. Please try again.";
            
            $communicationService->sendSms($user->phone, $message);

            // Send push notification
            $title = $user->language === 'ar' ? 'فشل في الدفع' : 'Payment Failed';
            $communicationService->sendPushNotification($user, $title, $message, [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,
                'type' => 'payment_failure'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send failure notification', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return [
            'payment',
            'transaction:' . $this->transactionId,
            'gateway:' . $this->gatewayType
        ];
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     */
    public function retryAfter(): int
    {
        return $this->attempts() * 60; // Wait 1, 2, 3 minutes between retries
    }
}
