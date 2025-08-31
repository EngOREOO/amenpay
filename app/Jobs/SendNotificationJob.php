<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Notification;
use App\Services\CommunicationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120; // 2 minutes
    public $maxExceptions = 3;

    protected $notificationId;
    protected $channels;
    protected $priority;

    /**
     * Create a new job instance.
     */
    public function __construct(int $notificationId, array $channels = ['push', 'email'], string $priority = 'normal')
    {
        $this->notificationId = $notificationId;
        $this->channels = $channels;
        $this->priority = $priority;
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(CommunicationService $communicationService): void
    {
        try {
            Log::info('Sending notification job started', [
                'notification_id' => $this->notificationId,
                'channels' => $this->channels,
                'priority' => $this->priority,
                'attempt' => $this->attempts()
            ]);

            // Get the notification
            $notification = Notification::findOrFail($this->notificationId);
            $user = $notification->user;

            if (!$user) {
                Log::error('User not found for notification', [
                    'notification_id' => $this->notificationId
                ]);
                return;
            }

            $results = [];
            $successCount = 0;

            // Send through each channel
            foreach ($this->channels as $channel) {
                $result = $this->sendThroughChannel($channel, $user, $notification, $communicationService);
                $results[$channel] = $result;
                
                if ($result['success']) {
                    $successCount++;
                }
            }

            // Update notification status
            $notification->update([
                'sent_at' => now(),
                'delivery_status' => $successCount > 0 ? 'delivered' : 'failed',
                'metadata' => array_merge($notification->metadata ?? [], [
                    'job_results' => $results,
                    'channels_attempted' => $this->channels,
                    'successful_channels' => $successCount,
                    'job_completed_at' => now()->toISOString()
                ])
            ]);

            Log::info('Notification job completed', [
                'notification_id' => $this->notificationId,
                'successful_channels' => $successCount,
                'total_channels' => count($this->channels),
                'results' => $results
            ]);

        } catch (\Exception $e) {
            Log::error('Notification job failed with exception', [
                'notification_id' => $this->notificationId,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts()
            ]);

            // Update notification status
            if (isset($notification)) {
                $notification->update([
                    'delivery_status' => 'failed',
                    'metadata' => array_merge($notification->metadata ?? [], [
                        'job_failed_at' => now()->toISOString(),
                        'failure_reason' => $e->getMessage()
                    ])
                ]);
            }

            // Re-throw exception for retry mechanism
            throw $e;
        }
    }

    /**
     * Send notification through specific channel
     */
    protected function sendThroughChannel(string $channel, User $user, Notification $notification, CommunicationService $communicationService): array
    {
        try {
            switch ($channel) {
                case 'sms':
                    return $this->sendSmsNotification($user, $notification, $communicationService);
                
                case 'email':
                    return $this->sendEmailNotification($user, $notification, $communicationService);
                
                case 'push':
                    return $this->sendPushNotification($user, $notification, $communicationService);
                
                default:
                    Log::warning('Unknown notification channel', ['channel' => $channel]);
                    return [
                        'success' => false,
                        'message' => 'Unknown channel: ' . $channel
                    ];
            }
        } catch (\Exception $e) {
            Log::error('Failed to send notification through channel', [
                'channel' => $channel,
                'user_id' => $user->id,
                'notification_id' => $notification->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Channel failed: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send SMS notification
     */
    protected function sendSmsNotification(User $user, Notification $notification, CommunicationService $communicationService): array
    {
        if (!$user->phone) {
            return [
                'success' => false,
                'message' => 'User has no phone number'
            ];
        }

        $message = $user->language === 'ar' ? $notification->message_ar : $notification->message_en;
        
        return $communicationService->sendSms($user->phone, $message);
    }

    /**
     * Send email notification
     */
    protected function sendEmailNotification(User $user, Notification $notification, CommunicationService $communicationService): array
    {
        if (!$user->email) {
            return [
                'success' => false,
                'message' => 'User has no email address'
            ];
        }

        $subject = $user->language === 'ar' ? $notification->title_ar : $notification->title_en;
        $template = 'notification';
        
        return $communicationService->sendEmail($user, $subject, $template, [
            'notification' => $notification,
            'message' => $user->language === 'ar' ? $notification->message_ar : $notification->message_en
        ]);
    }

    /**
     * Send push notification
     */
    protected function sendPushNotification(User $user, Notification $notification, CommunicationService $communicationService): array
    {
        $title = $user->language === 'ar' ? $notification->title_ar : $notification->title_en;
        $message = $user->language === 'ar' ? $notification->message_ar : $notification->message_en;
        
        return $communicationService->sendPushNotification($user, $title, $message, [
            'notification_id' => $notification->id,
            'type' => $notification->type,
            'data' => $notification->data
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Notification job failed permanently', [
            'notification_id' => $this->notificationId,
            'channels' => $this->channels,
            'error' => $exception->getMessage()
        ]);

        // Update notification status to failed
        try {
            $notification = Notification::find($this->notificationId);
            if ($notification) {
                $notification->update([
                    'delivery_status' => 'failed',
                    'metadata' => array_merge($notification->metadata ?? [], [
                        'failure_reason' => 'Job failed permanently: ' . $exception->getMessage(),
                        'job_failed_permanently_at' => now()->toISOString()
                    ])
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to update notification status after job failure', [
                'notification_id' => $this->notificationId,
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
            'notification',
            'notification_id:' . $this->notificationId,
            'channels:' . implode(',', $this->channels)
        ];
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     */
    public function retryAfter(): int
    {
        return $this->attempts() * 30; // Wait 30, 60, 90 seconds between retries
    }
}
