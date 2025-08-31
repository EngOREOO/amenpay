<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PushNotification;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;

class NotificationSystemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $wallet;
    protected $device;
    protected $announcement;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user with wallet
        $this->user = User::factory()->create([
            'phone' => '+966500000001',
            'email' => 'test@example.com',
            'name' => 'Test User',
            'language' => 'en',
            'status' => 'active'
        ]);

        $this->wallet = Wallet::factory()->create([
            'user_id' => $this->user->id,
            'balance' => 1000.00
        ]);

        // Create test push notification device
        $this->device = PushNotification::create([
            'user_id' => $this->user->id,
            'device_token' => 'test_device_token_123',
            'platform' => 'ios',
            'app_version' => '1.0.0',
            'device_model' => 'iPhone 12',
            'os_version' => '15.0',
            'status' => 'active',
            'preferences' => [
                'transaction_notifications' => true,
                'budget_alerts' => true,
                'goal_updates' => true,
                'security_alerts' => true,
                'promotional_notifications' => false
            ]
        ]);

        // Create test announcement
        $this->announcement = Announcement::create([
            'title_ar' => 'إعلان تجريبي',
            'title_en' => 'Test Announcement',
            'content_ar' => 'محتوى الإعلان التجريبي',
            'content_en' => 'Test announcement content',
            'type' => 'info',
            'priority' => 'medium',
            'status' => 'active',
            'published_at' => now(),
            'delivery_channels' => ['push', 'in_app'],
            'requires_acknowledgment' => false
        ]);
    }

    /** @test */
    public function push_notification_device_can_be_created()
    {
        $deviceData = [
            'user_id' => $this->user->id,
            'device_token' => 'new_device_token_456',
            'platform' => 'android',
            'app_version' => '2.0.0',
            'device_model' => 'Samsung Galaxy S21',
            'os_version' => '12.0'
        ];

        $device = PushNotification::create($deviceData);

        $this->assertDatabaseHas('push_notifications', $deviceData);
        $this->assertEquals('android', $device->platform);
        $this->assertEquals('Samsung Galaxy S21', $device->device_model);
    }

    /** @test */
    public function push_notification_device_can_be_registered()
    {
        $device = PushNotification::registerDevice(
            $this->user->id,
            'registered_token_789',
            'web',
            [
                'app_version' => '3.0.0',
                'device_model' => 'Chrome Browser',
                'os_version' => 'Windows 11'
            ]
        );

        $this->assertEquals('registered_token_789', $device->device_token);
        $this->assertEquals('web', $device->platform);
        $this->assertEquals('3.0.0', $device->app_version);
        $this->assertEquals('active', $device->status);
    }

    /** @test */
    public function push_notification_device_can_be_unregistered()
    {
        $success = PushNotification::unregisterDevice($this->user->id, $this->device->device_token);
        
        $this->assertTrue($success);
        $this->assertEquals('inactive', $this->device->fresh()->status);
    }

    /** @test */
    public function push_notification_device_can_update_preferences()
    {
        $newPreferences = [
            'transaction_notifications' => false,
            'budget_alerts' => false,
            'goal_updates' => true,
            'security_alerts' => true,
            'promotional_notifications' => true,
            'quiet_hours' => [
                'enabled' => true,
                'start_time' => '22:00',
                'end_time' => '08:00'
            ]
        ];

        $this->device->updatePreferences($newPreferences);

        $this->assertEquals($newPreferences, $this->device->fresh()->notification_preferences);
    }

    /** @test */
    public function push_notification_device_can_check_notification_type_support()
    {
        $this->assertTrue($this->device->supportsNotificationType('transaction'));
        $this->assertTrue($this->device->supportsNotificationType('budget'));
        $this->assertFalse($this->device->supportsNotificationType('promotional'));
    }

    /** @test */
    public function push_notification_device_can_check_quiet_hours()
    {
        // Test during quiet hours (22:00 - 08:00)
        $quietTime = Carbon::parse('23:00');
        $this->travelTo($quietTime);
        
        $this->assertFalse($this->device->isInQuietHours()); // Quiet hours not enabled by default
        
        // Enable quiet hours
        $this->device->updatePreferences([
            'quiet_hours' => [
                'enabled' => true,
                'start_time' => '22:00',
                'end_time' => '08:00'
            ]
        ]);
        
        $this->assertTrue($this->device->fresh()->isInQuietHours());
        
        // Test during active hours
        $activeTime = Carbon::parse('14:00');
        $this->travelTo($activeTime);
        
        $this->assertFalse($this->device->fresh()->isInQuietHours());
        
        $this->travelBack();
    }

    /** @test */
    public function push_notification_device_can_update_last_used()
    {
        $originalLastUsed = $this->device->last_used_at;
        
        $this->device->updateLastUsed();
        
        $this->assertNotEquals($originalLastUsed, $this->device->fresh()->last_used_at);
    }

    /** @test */
    public function push_notification_device_can_check_active_status()
    {
        $this->assertTrue($this->device->isActive());
        
        $this->device->update(['status' => 'inactive']);
        $this->assertFalse($this->device->fresh()->isActive());
        
        $this->device->update(['status' => 'expired']);
        $this->assertFalse($this->device->fresh()->isActive());
    }

    /** @test */
    public function push_notification_device_can_get_device_summary()
    {
        $summary = $this->device->getDeviceSummary();
        
        $this->assertArrayHasKey('platform', $summary);
        $this->assertArrayHasKey('app_version', $summary);
        $this->assertArrayHasKey('device_model', $summary);
        $this->assertArrayHasKey('os_version', $summary);
        $this->assertArrayHasKey('status', $summary);
        $this->assertArrayHasKey('is_active', $summary);
    }

    /** @test */
    public function push_notification_device_can_be_filtered_by_scope()
    {
        $activeDevices = PushNotification::active()->get();
        $this->assertTrue($activeDevices->contains($this->device));

        $iosDevices = PushNotification::byPlatform('ios')->get();
        $this->assertTrue($iosDevices->contains($this->device));

        $userDevices = PushNotification::byUser($this->user->id)->get();
        $this->assertTrue($userDevices->contains($this->device));
    }

    /** @test */
    public function push_notification_device_can_get_statistics()
    {
        $stats = PushNotification::getStatistics();
        
        $this->assertArrayHasKey('total_devices', $stats);
        $this->assertArrayHasKey('active_devices', $stats);
        $this->assertArrayHasKey('inactive_devices', $stats);
        $this->assertArrayHasKey('devices_by_platform', $stats);
        $this->assertArrayHasKey('activation_rate', $stats);
        
        $this->assertEquals(1, $stats['total_devices']);
        $this->assertEquals(1, $stats['active_devices']);
        $this->assertEquals(100, $stats['activation_rate']);
    }

    /** @test */
    public function push_notification_device_can_cleanup_expired()
    {
        $expiredDevice = PushNotification::create([
            'user_id' => $this->user->id,
            'device_token' => 'expired_token',
            'platform' => 'android',
            'expires_at' => now()->subDay(),
            'status' => 'active'
        ]);

        $cleanedCount = PushNotification::cleanupExpiredDevices();
        
        $this->assertEquals(1, $cleanedCount);
        $this->assertEquals('expired', $expiredDevice->fresh()->status);
    }

    /** @test */
    public function announcement_can_be_created()
    {
        $announcementData = [
            'title_ar' => 'إعلان جديد',
            'title_en' => 'New Announcement',
            'content_ar' => 'محتوى الإعلان الجديد',
            'content_en' => 'New announcement content',
            'type' => 'warning',
            'priority' => 'high',
            'status' => 'draft'
        ];

        $announcement = Announcement::create($announcementData);

        $this->assertDatabaseHas('announcements', $announcementData);
        $this->assertEquals('New Announcement', $announcement->title);
        $this->assertEquals('warning', $announcement->type);
    }

    /** @test */
    public function announcement_can_get_localized_title_and_content()
    {
        // Test English locale
        app()->setLocale('en');
        $this->assertEquals('Test Announcement', $this->announcement->title);
        $this->assertEquals('Test announcement content', $this->announcement->content);

        // Test Arabic locale
        app()->setLocale('ar');
        $this->assertEquals('إعلان تجريبي', $this->announcement->title);
        $this->assertEquals('محتوى الإعلان التجريبي', $this->announcement->content);
    }

    /** @test */
    public function announcement_can_check_publication_status()
    {
        $this->assertTrue($this->announcement->is_published);
        $this->assertFalse($this->announcement->is_expired);
        $this->assertTrue($this->announcement->is_active);

        // Test draft announcement
        $draftAnnouncement = Announcement::create([
            'title_ar' => 'مسودة',
            'title_en' => 'Draft',
            'content_ar' => 'محتوى المسودة',
            'content_en' => 'Draft content',
            'type' => 'info',
            'status' => 'draft'
        ]);

        $this->assertFalse($draftAnnouncement->is_published);
        $this->assertFalse($draftAnnouncement->is_active);
    }

    /** @test */
    public function announcement_can_be_published()
    {
        $draftAnnouncement = Announcement::create([
            'title_ar' => 'مسودة للنشر',
            'title_en' => 'Draft to Publish',
            'content_ar' => 'محتوى للنشر',
            'content_en' => 'Content to publish',
            'type' => 'info',
            'status' => 'draft'
        ]);

        $draftAnnouncement->publish();

        $this->assertEquals('active', $draftAnnouncement->fresh()->status);
        $this->assertNotNull($draftAnnouncement->fresh()->published_at);
    }

    /** @test */
    public function announcement_can_be_scheduled()
    {
        $futureDate = now()->addDay();
        
        $this->announcement->schedule($futureDate);
        
        $this->assertEquals('scheduled', $this->announcement->fresh()->status);
        $this->assertEquals($futureDate->toDateString(), $this->announcement->fresh()->published_at->toDateString());
    }

    /** @test */
    public function announcement_can_check_user_targeting()
    {
        // Test announcement with no targeting (should target all users)
        $this->assertTrue($this->announcement->targetsUser($this->user));

        // Test language targeting
        $languageTargetedAnnouncement = Announcement::create([
            'title_ar' => 'إعلان بالعربية',
            'title_en' => 'Arabic Announcement',
            'content_ar' => 'محتوى بالعربية',
            'content_en' => 'Arabic content',
            'type' => 'info',
            'status' => 'active',
            'published_at' => now(),
            'target_audience' => ['languages' => ['ar']]
        ]);

        $this->assertFalse($languageTargetedAnnouncement->targetsUser($this->user)); // User language is 'en'
        
        // Update user language and test again
        $this->user->update(['language' => 'ar']);
        $this->assertTrue($languageTargetedAnnouncement->targetsUser($this->user->fresh()));
    }

    /** @test */
    public function announcement_can_check_delivery_channel()
    {
        $this->assertTrue($this->announcement->shouldDeliverVia('push'));
        $this->assertTrue($this->announcement->shouldDeliverVia('in_app'));
        $this->assertTrue($this->announcement->shouldDeliverVia('sms')); // No restriction

        // Test with channel restriction
        $restrictedAnnouncement = Announcement::create([
            'title_ar' => 'إعلان مقيد',
            'title_en' => 'Restricted Announcement',
            'content_ar' => 'محتوى مقيد',
            'content_en' => 'Restricted content',
            'type' => 'info',
            'status' => 'active',
            'published_at' => now(),
            'delivery_channels' => ['push']
        ]);

        $this->assertTrue($restrictedAnnouncement->shouldDeliverVia('push'));
        $this->assertFalse($restrictedAnnouncement->shouldDeliverVia('sms'));
    }

    /** @test */
    public function announcement_can_increment_acknowledgment()
    {
        $this->announcement->update(['requires_acknowledgment' => true]);
        
        $this->announcement->incrementAcknowledgment();
        
        $this->assertEquals(1, $this->announcement->fresh()->acknowledged_count);
    }

    /** @test */
    public function announcement_can_get_summary()
    {
        $summary = $this->announcement->getSummary();
        
        $this->assertArrayHasKey('id', $summary);
        $this->assertArrayHasKey('title', $summary);
        $this->assertArrayHasKey('type', $summary);
        $this->assertArrayHasKey('priority', $summary);
        $this->assertArrayHasKey('status', $summary);
        $this->assertArrayHasKey('is_published', $summary);
        $this->assertArrayHasKey('is_expired', $summary);
    }

    /** @test */
    public function announcement_can_be_filtered_by_scope()
    {
        $activeAnnouncements = Announcement::active()->get();
        $this->assertTrue($activeAnnouncements->contains($this->announcement));

        $publishedAnnouncements = Announcement::published()->get();
        $this->assertTrue($publishedAnnouncements->contains($this->announcement));

        $infoAnnouncements = Announcement::byType('info')->get();
        $this->assertTrue($infoAnnouncements->contains($this->announcement));

        $mediumPriorityAnnouncements = Announcement::byPriority('medium')->get();
        $this->assertTrue($mediumPriorityAnnouncements->contains($this->announcement));
    }

    /** @test */
    public function announcement_can_get_statistics()
    {
        $stats = Announcement::getStatistics();
        
        $this->assertArrayHasKey('total_announcements', $stats);
        $this->assertArrayHasKey('active_announcements', $stats);
        $this->assertArrayHasKey('draft_announcements', $stats);
        $this->assertArrayHasKey('announcements_by_type', $stats);
        $this->assertArrayHasKey('announcements_by_priority', $stats);
        $this->assertArrayHasKey('publication_rate', $stats);
        
        $this->assertEquals(1, $stats['total_announcements']);
        $this->assertEquals(1, $stats['active_announcements']);
        $this->assertEquals(100, $stats['publication_rate']);
    }

    /** @test */
    public function announcement_can_create_system_announcement()
    {
        $systemAnnouncement = Announcement::createSystemAnnouncement(
            'إعلان النظام',
            'System Announcement',
            'محتوى النظام',
            'System content',
            'success',
            'high',
            ['push', 'email'],
            true
        );

        $this->assertEquals('System Announcement', $systemAnnouncement->title);
        $this->assertEquals('success', $systemAnnouncement->type);
        $this->assertEquals('high', $systemAnnouncement->priority);
        $this->assertEquals('active', $systemAnnouncement->status);
        $this->assertTrue($systemAnnouncement->requires_acknowledgment);
    }

    /** @test */
    public function announcement_can_create_maintenance_announcement()
    {
        $startTime = now()->addHour();
        $endTime = now()->addHours(3);
        
        $maintenanceAnnouncement = Announcement::createMaintenanceAnnouncement(
            'صيانة النظام',
            'System Maintenance',
            'سيتم إجراء صيانة للنظام',
            'System maintenance will be performed',
            $startTime,
            $endTime,
            'urgent'
        );

        $this->assertEquals('System Maintenance', $maintenanceAnnouncement->title);
        $this->assertEquals('maintenance', $maintenanceAnnouncement->type);
        $this->assertEquals('urgent', $maintenanceAnnouncement->priority);
        $this->assertTrue($maintenanceAnnouncement->requires_acknowledgment);
        $this->assertEquals($startTime, $maintenanceAnnouncement->published_at);
        $this->assertEquals($endTime, $maintenanceAnnouncement->expires_at);
    }

    /** @test */
    public function announcement_can_cleanup_expired()
    {
        $expiredAnnouncement = Announcement::create([
            'title_ar' => 'إعلان منتهي',
            'title_en' => 'Expired Announcement',
            'content_ar' => 'محتوى منتهي',
            'content_en' => 'Expired content',
            'type' => 'info',
            'status' => 'active',
            'published_at' => now(),
            'expires_at' => now()->subDay()
        ]);

        $cleanedCount = Announcement::cleanupExpired();
        
        $this->assertEquals(1, $cleanedCount);
        $this->assertEquals('expired', $expiredAnnouncement->fresh()->status);
    }

    /** @test */
    public function notification_system_can_get_active_announcements_for_user()
    {
        $activeAnnouncements = Announcement::getActiveForUser($this->user);
        
        $this->assertTrue($activeAnnouncements->contains($this->announcement));
        $this->assertEquals(1, $activeAnnouncements->count());
    }

    /** @test */
    public function notification_system_can_get_user_devices()
    {
        $userDevices = PushNotification::getActiveDevicesForUser($this->user->id);
        
        $this->assertTrue($userDevices->contains($this->device));
        $this->assertEquals(1, $userDevices->count());
    }

    /** @test */
    public function notification_system_can_get_delivery_status()
    {
        $notification = Notification::create([
            'user_id' => $this->user->id,
            'title' => 'Test Notification',
            'type' => 'info',
            'is_read' => false
        ]);

        $this->assertInstanceOf(Notification::class, $this->user->notifications->first());
        $this->assertEquals('Test Notification', $this->user->notifications->first()->title);
        $this->assertFalse($this->user->notifications->first()->is_read);
    }

    /** @test */
    public function notification_system_factory_creates_valid_data()
    {
        $factoryDevice = PushNotification::factory()->create();
        
        $this->assertNotNull($factoryDevice->device_token);
        $this->assertNotNull($factoryDevice->platform);
        $this->assertGreaterThan(0, strlen($factoryDevice->device_token));

        $factoryAnnouncement = Announcement::factory()->create();
        
        $this->assertNotNull($factoryAnnouncement->title_ar);
        $this->assertNotNull($factoryAnnouncement->title_en);
        $this->assertGreaterThan(0, strlen($factoryAnnouncement->title_ar));
    }
}
