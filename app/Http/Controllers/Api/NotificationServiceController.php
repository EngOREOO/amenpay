<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use App\Models\Announcement;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NotificationServiceController extends Controller
{
    /**
     * Register device for push notifications.
     */
    public function registerDevice(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'device_token' => 'required|string|max:500',
            'platform' => 'required|in:ios,android,web',
            'app_version' => 'nullable|string|max:50',
            'device_model' => 'nullable|string|max:100',
            'os_version' => 'nullable|string|max:50',
            'preferences' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        
        try {
            $device = PushNotification::registerDevice(
                $user->id,
                $request->device_token,
                $request->platform,
                [
                    'app_version' => $request->app_version,
                    'device_model' => $request->device_model,
                    'os_version' => $request->os_version,
                    'preferences' => $request->preferences
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Device registered successfully',
                'data' => $device->getDeviceSummary()
            ], 201);

        } catch (\Exception $e) {
            Log::error('Device registration failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to register device'
            ], 500);
        }
    }

    /**
     * Unregister device for push notifications.
     */
    public function unregisterDevice(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'device_token' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        
        try {
            $success = PushNotification::unregisterDevice($user->id, $request->device_token);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Device unregistered successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Device not found'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Device unregistration failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to unregister device'
            ], 500);
        }
    }

    /**
     * Update device preferences.
     */
    public function updateDevicePreferences(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'device_token' => 'required|string|max:500',
            'preferences' => 'required|array',
            'preferences.transaction_notifications' => 'boolean',
            'preferences.budget_alerts' => 'boolean',
            'preferences.goal_updates' => 'boolean',
            'preferences.security_alerts' => 'boolean',
            'preferences.promotional_notifications' => 'boolean',
            'preferences.quiet_hours' => 'array',
            'preferences.quiet_hours.enabled' => 'boolean',
            'preferences.quiet_hours.start_time' => 'string|regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/',
            'preferences.quiet_hours.end_time' => 'string|regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        
        try {
            $device = PushNotification::where('user_id', $user->id)
                ->where('device_token', $request->device_token)
                ->first();

            if (!$device) {
                return response()->json([
                    'success' => false,
                    'message' => 'Device not found'
                ], 404);
            }

            $device->updatePreferences($request->preferences);

            return response()->json([
                'success' => true,
                'message' => 'Device preferences updated successfully',
                'data' => $device->notification_preferences
            ]);

        } catch (\Exception $e) {
            Log::error('Device preferences update failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update device preferences'
            ], 500);
        }
    }

    /**
     * Get user's devices.
     */
    public function getUserDevices(Request $request): JsonResponse
    {
        $user = $request->user();
        
        try {
            $devices = PushNotification::where('user_id', $user->id)
                ->orderBy('last_used_at', 'desc')
                ->get()
                ->map(function ($device) {
                    return $device->getDeviceSummary();
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'devices' => $devices,
                    'total_devices' => $devices->count(),
                    'active_devices' => $devices->where('is_active', true)->count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Get user devices failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get user devices'
            ], 500);
        }
    }

    /**
     * Get active announcements for user.
     */
    public function getAnnouncements(Request $request): JsonResponse
    {
        $user = $request->user();
        
        try {
            $announcements = Announcement::getActiveForUser($user)
                ->map(function ($announcement) {
                    return $announcement->getSummary();
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'announcements' => $announcements,
                    'total_announcements' => $announcements->count(),
                    'urgent_announcements' => $announcements->where('priority', 'urgent')->count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Get announcements failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get announcements'
            ], 500);
        }
    }

    /**
     * Acknowledge announcement.
     */
    public function acknowledgeAnnouncement(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        
        try {
            $announcement = Announcement::findOrFail($id);
            
            if (!$announcement->targetsUser($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Announcement not found'
                ], 404);
            }

            if ($announcement->requires_acknowledgment) {
                $announcement->incrementAcknowledgment();
            }

            return response()->json([
                'success' => true,
                'message' => 'Announcement acknowledged successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Announcement acknowledgment failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to acknowledge announcement'
            ], 500);
        }
    }

    /**
     * Send test push notification.
     */
    public function sendTestNotification(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'device_token' => 'required|string|max:500',
            'message' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        
        try {
            $device = PushNotification::where('user_id', $user->id)
                ->where('device_token', $request->device_token)
                ->first();

            if (!$device) {
                return response()->json([
                    'success' => false,
                    'message' => 'Device not found'
                ], 404);
            }

            // Send test notification
            $this->sendPushNotification($device, [
                'title' => 'Test Notification',
                'body' => $request->message,
                'type' => 'test'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Test notification sent successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Test notification failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test notification'
            ], 500);
        }
    }

    /**
     * Get notification delivery status.
     */
    public function getDeliveryStatus(Request $request): JsonResponse
    {
        $user = $request->user();
        
        try {
            $notifications = Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            $deliveryStats = [
                'total_notifications' => $notifications->count(),
                'read_notifications' => $notifications->where('is_read', true)->count(),
                'unread_notifications' => $notifications->where('is_read', false)->count(),
                'notifications_by_type' => $notifications->groupBy('type')->map->count(),
                'recent_deliveries' => $notifications->take(10)->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->type,
                        'title' => $notification->title,
                        'delivered_at' => $notification->created_at->format('Y-m-d H:i:s'),
                        'is_read' => $notification->is_read
                    ];
                })
            ];

            return response()->json([
                'success' => true,
                'data' => $deliveryStats
            ]);

        } catch (\Exception $e) {
            Log::error('Get delivery status failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get delivery status'
            ], 500);
        }
    }

    /**
     * Send push notification to device.
     */
    private function sendPushNotification(PushNotification $device, array $data): bool
    {
        try {
            // Check if device supports this notification type
            $notificationType = $data['type'] ?? 'general';
            if (!$device->supportsNotificationType($notificationType)) {
                Log::info("Device {$device->id} does not support notification type: {$notificationType}");
                return false;
            }

            // Check if device is in quiet hours
            if ($device->isInQuietHours()) {
                Log::info("Device {$device->id} is in quiet hours, skipping notification");
                return false;
            }

            // Update device last used timestamp
            $device->updateLastUsed();

            // In a real application, you would integrate with FCM (Firebase) or APNS (Apple)
            // For now, we'll just log the notification
            Log::info("Push notification sent to device {$device->id}", [
                'device_token' => $device->device_token,
                'platform' => $device->platform,
                'notification_data' => $data
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Failed to send push notification to device {$device->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send SMS notification.
     */
    private function sendSmsNotification(string $phoneNumber, string $message): bool
    {
        try {
            // In a real application, you would integrate with SMS gateway (Twilio, etc.)
            // For now, we'll just log the SMS
            Log::info("SMS notification sent", [
                'phone_number' => $phoneNumber,
                'message' => $message
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Failed to send SMS notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email notification.
     */
    private function sendEmailNotification(string $email, string $subject, string $message): bool
    {
        try {
            // In a real application, you would use Laravel's Mail facade
            // For now, we'll just log the email
            Log::info("Email notification sent", [
                'email' => $email,
                'subject' => $subject,
                'message' => $message
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Failed to send email notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deliver notification through multiple channels.
     */
    public function deliverNotification($user, array $notificationData, array $channels = ['push', 'in_app']): array
    {
        $deliveryResults = [];

        foreach ($channels as $channel) {
            switch ($channel) {
                case 'push':
                    $devices = PushNotification::getActiveDevicesForUser($user->id);
                    $pushResults = [];
                    
                    foreach ($devices as $device) {
                        $pushResults[] = $this->sendPushNotification($device, $notificationData);
                    }
                    
                    $deliveryResults['push'] = [
                        'success' => count(array_filter($pushResults)),
                        'total' => count($pushResults),
                        'devices' => $devices->count()
                    ];
                    break;

                case 'sms':
                    if ($user->phone) {
                        $deliveryResults['sms'] = [
                            'success' => $this->sendSmsNotification($user->phone, $notificationData['body'] ?? ''),
                            'phone' => $user->phone
                        ];
                    }
                    break;

                case 'email':
                    if ($user->email) {
                        $deliveryResults['email'] = [
                            'success' => $this->sendEmailNotification(
                                $user->email,
                                $notificationData['title'] ?? '',
                                $notificationData['body'] ?? ''
                            ),
                            'email' => $user->email
                        ];
                    }
                    break;

                case 'in_app':
                    // In-app notifications are already handled by the Notification model
                    $deliveryResults['in_app'] = [
                        'success' => true,
                        'message' => 'In-app notification created'
                    ];
                    break;
            }
        }

        return $deliveryResults;
    }

    /**
     * Get notification service statistics.
     */
    public function getServiceStatistics(Request $request): JsonResponse
    {
        try {
            $pushStats = PushNotification::getStatistics();
            $announcementStats = Announcement::getStatistics();
            
            $totalNotifications = Notification::count();
            $unreadNotifications = Notification::where('is_read', false)->count();

            $stats = [
                'push_notifications' => $pushStats,
                'announcements' => $announcementStats,
                'in_app_notifications' => [
                    'total' => $totalNotifications,
                    'unread' => $unreadNotifications,
                    'read' => $totalNotifications - $unreadNotifications
                ],
                'delivery_channels' => [
                    'push' => $pushStats['active_devices'],
                    'sms' => 'configured', // Would check SMS gateway status
                    'email' => 'configured', // Would check email service status
                    'in_app' => 'always_available'
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Get service statistics failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get service statistics'
            ], 500);
        }
    }
}
