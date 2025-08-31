<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Card;
use App\Models\Notification;
use App\Models\PushNotification;
use App\Models\Announcement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $wallet;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user with wallet
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
    }

    /** @test */
    public function user_can_be_created_with_valid_data()
    {
        $userData = [
            'phone' => '+966500000002',
            'email' => 'newuser@example.com',
            'name' => 'New User',
            'language' => 'ar',
            'national_id' => '1234567890'
        ];

        $user = User::create($userData);

        $this->assertDatabaseHas('users', $userData);
        $this->assertEquals('New User', $user->name);
        $this->assertEquals('ar', $user->language);
        $this->assertFalse($user->is_verified);
    }

    /** @test */
    public function user_phone_must_be_unique()
    {
        $duplicateData = [
            'phone' => $this->user->phone,
            'email' => 'different@example.com',
            'name' => 'Different User'
        ];

        $this->expectException(\Illuminate\Database\QueryException::class);
        User::create($duplicateData);
    }

    /** @test */
    public function user_email_must_be_unique()
    {
        $duplicateData = [
            'phone' => '+966500000003',
            'email' => $this->user->email,
            'name' => 'Different User'
        ];

        $this->expectException(\Illuminate\Database\QueryException::class);
        User::create($duplicateData);
    }

    /** @test */
    public function user_can_have_wallet()
    {
        $this->assertInstanceOf(Wallet::class, $this->user->wallet);
        $this->assertEquals(1000.00, $this->user->wallet->balance);
    }

    /** @test */
    public function user_can_have_cards()
    {
        $card = Card::factory()->create([
            'user_id' => $this->user->id,
            'card_number' => '4111111111111111',
            'card_type' => 'credit',
            'brand' => 'Visa'
        ]);

        $this->assertInstanceOf(Card::class, $this->user->cards->first());
        $this->assertEquals('Visa', $this->user->cards->first()->brand);
    }

    /** @test */
    public function user_can_have_notifications()
    {
        $notification = Notification::factory()->create([
            'user_id' => $this->user->id,
            'title' => 'Test Notification',
            'type' => 'info'
        ]);

        $this->assertInstanceOf(Notification::class, $this->user->notifications->first());
        $this->assertEquals('Test Notification', $this->user->notifications->first()->title);
    }

    /** @test */
    public function user_can_have_push_notification_devices()
    {
        $device = PushNotification::factory()->create([
            'user_id' => $this->user->id,
            'device_token' => 'test_token_123',
            'platform' => 'ios'
        ]);

        $this->assertInstanceOf(PushNotification::class, $this->user->pushNotificationDevices->first());
        $this->assertEquals('ios', $this->user->pushNotificationDevices->first()->platform);
    }

    /** @test */
    public function user_can_have_announcements()
    {
        $announcement = Announcement::factory()->create([
            'title_ar' => 'إعلان تجريبي',
            'title_en' => 'Test Announcement',
            'content_ar' => 'محتوى الإعلان',
            'content_en' => 'Announcement content',
            'type' => 'info',
            'status' => 'active',
            'published_at' => now()
        ]);

        $this->assertInstanceOf(Announcement::class, $this->user->announcements->first());
        $this->assertEquals('Test Announcement', $this->user->announcements->first()->title);
    }

    /** @test */
    public function user_can_have_transactions()
    {
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $this->user->transactions);
    }

    /** @test */
    public function user_can_have_budgets()
    {
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $this->user->budgets);
    }

    /** @test */
    public function user_can_have_goals()
    {
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $this->user->goals);
    }

    /** @test */
    public function user_can_have_financial_insights()
    {
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $this->user->financialInsights);
    }

    /** @test */
    public function user_can_have_spending_patterns()
    {
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $this->user->spendingPatterns);
    }

    /** @test */
    public function user_language_can_be_updated()
    {
        $this->user->update(['language' => 'ar']);
        
        $this->assertEquals('ar', $this->user->fresh()->language);
    }

    /** @test */
    public function user_status_can_be_updated()
    {
        $this->user->update(['status' => 'suspended']);
        
        $this->assertEquals('suspended', $this->user->fresh()->status);
    }

    /** @test */
    public function user_verification_status_can_be_updated()
    {
        $this->user->update([
            'is_verified' => true,
            'phone_verified_at' => now()
        ]);
        
        $this->assertTrue($this->user->fresh()->is_verified);
        $this->assertNotNull($this->user->fresh()->phone_verified_at);
    }

    /** @test */
    public function user_last_login_can_be_updated()
    {
        $this->user->update(['last_login_at' => now()]);
        
        $this->assertNotNull($this->user->fresh()->last_login_at);
    }

    /** @test */
    public function user_avatar_can_be_set()
    {
        $avatarPath = 'avatars/user123.jpg';
        $this->user->update(['avatar' => $avatarPath]);
        
        $this->assertEquals($avatarPath, $this->user->fresh()->avatar);
    }

    /** @test */
    public function user_national_id_can_be_set()
    {
        $nationalId = '1234567890';
        $this->user->update(['national_id' => $nationalId]);
        
        $this->assertEquals($nationalId, $this->user->fresh()->national_id);
    }

    /** @test */
    public function user_can_be_soft_deleted()
    {
        $this->user->delete();
        
        $this->assertSoftDeleted($this->user);
    }

    /** @test */
    public function user_can_be_restored()
    {
        $this->user->delete();
        $this->user->restore();
        
        $this->assertNotSoftDeleted($this->user);
    }

    /** @test */
    public function user_can_be_permanently_deleted()
    {
        $userId = $this->user->id;
        $this->user->forceDelete();
        
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    /** @test */
    public function user_relationships_are_cascaded_on_delete()
    {
        $userId = $this->user->id;
        
        // Create related records
        Card::factory()->create(['user_id' => $userId]);
        Notification::factory()->create(['user_id' => $userId]);
        PushNotification::factory()->create(['user_id' => $userId]);
        
        // Delete user
        $this->user->forceDelete();
        
        // Check that related records are also deleted
        $this->assertDatabaseMissing('cards', ['user_id' => $userId]);
        $this->assertDatabaseMissing('notifications', ['user_id' => $userId]);
        $this->assertDatabaseMissing('push_notifications', ['user_id' => $userId]);
    }

    /** @test */
    public function user_can_be_searched_by_phone()
    {
        $foundUser = User::where('phone', '+966500000001')->first();
        
        $this->assertNotNull($foundUser);
        $this->assertEquals('Test User', $foundUser->name);
    }

    /** @test */
    public function user_can_be_searched_by_email()
    {
        $foundUser = User::where('email', 'test@example.com')->first();
        
        $this->assertNotNull($foundUser);
        $this->assertEquals('Test User', $foundUser->name);
    }

    /** @test */
    public function user_can_be_searched_by_national_id()
    {
        $this->user->update(['national_id' => '1234567890']);
        
        $foundUser = User::where('national_id', '1234567890')->first();
        
        $this->assertNotNull($foundUser);
        $this->assertEquals('Test User', $foundUser->name);
    }

    /** @test */
    public function user_can_be_filtered_by_status()
    {
        $activeUsers = User::where('status', 'active')->get();
        
        $this->assertTrue($activeUsers->contains($this->user));
    }

    /** @test */
    public function user_can_be_filtered_by_language()
    {
        $englishUsers = User::where('language', 'en')->get();
        
        $this->assertTrue($englishUsers->contains($this->user));
    }

    /** @test */
    public function user_can_be_filtered_by_verification_status()
    {
        $verifiedUsers = User::where('is_verified', true)->get();
        $unverifiedUsers = User::where('is_verified', false)->get();
        
        $this->assertTrue($unverifiedUsers->contains($this->user));
        $this->assertFalse($verifiedUsers->contains($this->user));
    }

    /** @test */
    public function user_can_be_ordered_by_creation_date()
    {
        $newUser = User::factory()->create([
            'phone' => '+966500000004',
            'email' => 'newest@example.com',
            'name' => 'Newest User'
        ]);

        $usersByDate = User::orderBy('created_at', 'desc')->get();
        
        $this->assertEquals($newUser->id, $usersByDate->first()->id);
        $this->assertEquals($this->user->id, $usersByDate->last()->id);
    }

    /** @test */
    public function user_can_be_ordered_by_name()
    {
        $userA = User::factory()->create([
            'phone' => '+966500000005',
            'email' => 'userA@example.com',
            'name' => 'Alice User'
        ]);

        $userB = User::factory()->create([
            'phone' => '+966500000006',
            'email' => 'userB@example.com',
            'name' => 'Bob User'
        ]);

        $usersByName = User::orderBy('name', 'asc')->get();
        
        $this->assertEquals($userA->id, $usersByName->first()->id);
        $this->assertEquals($userB->id, $usersByName->last()->id);
    }

    /** @test */
    public function user_can_be_paginated()
    {
        // Create additional users
        User::factory()->count(15)->create();

        $paginatedUsers = User::paginate(10);
        
        $this->assertEquals(10, $paginatedUsers->count());
        $this->assertTrue($paginatedUsers->hasPages());
    }

    /** @test */
    public function user_can_be_counted()
    {
        $initialCount = User::count();
        
        User::factory()->count(5)->create();
        
        $newCount = User::count();
        
        $this->assertEquals($initialCount + 5, $newCount);
    }

    /** @test */
    public function user_can_be_aggregated()
    {
        $userCount = User::count();
        $verifiedCount = User::where('is_verified', true)->count();
        $unverifiedCount = User::where('is_verified', false)->count();
        
        $this->assertEquals($userCount, $verifiedCount + $unverifiedCount);
    }

    /** @test */
    public function user_can_be_grouped_by_status()
    {
        $usersByStatus = User::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        $this->assertArrayHasKey('active', $usersByStatus);
        $this->assertGreaterThan(0, $usersByStatus['active']);
    }

    /** @test */
    public function user_can_be_grouped_by_language()
    {
        $usersByLanguage = User::selectRaw('language, COUNT(*) as count')
            ->groupBy('language')
            ->pluck('count', 'language')
            ->toArray();
        
        $this->assertArrayHasKey('en', $usersByLanguage);
        $this->assertGreaterThan(0, $usersByLanguage['en']);
    }

    /** @test */
    public function user_factory_creates_valid_data()
    {
        $factoryUser = User::factory()->create();
        
        $this->assertNotNull($factoryUser->phone);
        $this->assertNotNull($factoryUser->name);
        $this->assertMatchesRegularExpression('/^\+966[0-9]{9}$/', $factoryUser->phone);
        $this->assertGreaterThan(0, strlen($factoryUser->name));
    }

    /** @test */
    public function user_factory_can_create_with_specific_data()
    {
        $specificUser = User::factory()->create([
            'phone' => '+966500000007',
            'name' => 'Specific User',
            'language' => 'ar'
        ]);
        
        $this->assertEquals('+966500000007', $specificUser->phone);
        $this->assertEquals('Specific User', $specificUser->name);
        $this->assertEquals('ar', $specificUser->language);
    }

    /** @test */
    public function user_factory_can_create_multiple_users()
    {
        $users = User::factory()->count(3)->create();
        
        $this->assertCount(3, $users);
        $this->assertInstanceOf(User::class, $users->first());
    }
}
