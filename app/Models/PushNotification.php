<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PushNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_token',
        'platform',
        'app_version',
        'device_model',
        'os_version',
        'status',
        'last_used_at',
        'expires_at',
        'preferences'
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
        'preferences' => 'array'
    ];

    protected $appends = [
        'is_expired',
        'days_since_last_used',
        'notification_preferences'
    ];

    /**
     * Get the user that owns the push notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if device token is expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get days since last used.
     */
    public function getDaysSinceLastUsedAttribute(): ?int
    {
        return $this->last_used_at ? $this->last_used_at->diffInDays(now()) : null;
    }

    /**
     * Get notification preferences for this device.
     */
    public function getNotificationPreferencesAttribute(): array
    {
        $defaultPreferences = [
            'transaction_notifications' => true,
            'budget_alerts' => true,
            'goal_updates' => true,
            'security_alerts' => true,
            'promotional_notifications' => false,
            'quiet_hours' => [
                'enabled' => false,
                'start_time' => '22:00',
                'end_time' => '08:00'
            ]
        ];

        return array_merge($defaultPreferences, $this->preferences ?? []);
    }

    /**
     * Update last used timestamp.
     */
    public function updateLastUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Check if device is active and valid.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && !$this->is_expired;
    }

    /**
     * Check if device supports specific notification type.
     */
    public function supportsNotificationType(string $type): bool
    {
        $preferences = $this->notification_preferences;
        
        return match($type) {
            'transaction' => $preferences['transaction_notifications'] ?? true,
            'budget' => $preferences['budget_alerts'] ?? true,
            'goal' => $preferences['goal_updates'] ?? true,
            'security' => $preferences['security_alerts'] ?? true,
            'promotional' => $preferences['promotional_notifications'] ?? false,
            default => true
        };
    }

    /**
     * Check if device is in quiet hours.
     */
    public function isInQuietHours(): bool
    {
        $preferences = $this->notification_preferences;
        
        if (!($preferences['quiet_hours']['enabled'] ?? false)) {
            return false;
        }

        $now = now();
        $startTime = Carbon::parse($preferences['quiet_hours']['start_time']);
        $endTime = Carbon::parse($preferences['quiet_hours']['end_time']);

        // Handle overnight quiet hours
        if ($startTime->gt($endTime)) {
            return $now->gte($startTime) || $now->lte($endTime);
        }

        return $now->between($startTime, $endTime);
    }

    /**
     * Update notification preferences.
     */
    public function updatePreferences(array $preferences): void
    {
        $this->update(['preferences' => $preferences]);
    }

    /**
     * Mark device as inactive.
     */
    public function markInactive(): void
    {
        $this->update(['status' => 'inactive']);
    }

    /**
     * Mark device as expired.
     */
    public function markExpired(): void
    {
        $this->update(['status' => 'expired']);
    }

    /**
     * Get device summary.
     */
    public function getDeviceSummary(): array
    {
        return [
            'platform' => $this->platform,
            'app_version' => $this->app_version,
            'device_model' => $this->device_model,
            'os_version' => $this->os_version,
            'status' => $this->status,
            'last_used' => $this->last_used_at?->diffForHumans(),
            'is_active' => $this->isActive()
        ];
    }

    /**
     * Scope for active devices.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope for devices by platform.
     */
    public function scopeByPlatform($query, $platform)
    {
        return $query->where('platform', $platform);
    }

    /**
     * Scope for devices by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for devices that support specific notification type.
     */
    public function scopeSupportingNotificationType($query, $type)
    {
        return $query->whereJsonContains('preferences->' . $type . '_notifications', true);
    }

    /**
     * Scope for devices not in quiet hours.
     */
    public function scopeNotInQuietHours($query)
    {
        // This would need to be implemented at the application level
        // as we can't easily query JSON conditions for time-based logic
        return $query;
    }

    /**
     * Get push notification statistics.
     */
    public static function getStatistics(): array
    {
        $total = static::count();
        $active = static::active()->count();
        $byPlatform = static::selectRaw('platform, COUNT(*) as count')
            ->groupBy('platform')
            ->pluck('count', 'platform')
            ->toArray();

        return [
            'total_devices' => $total,
            'active_devices' => $active,
            'inactive_devices' => $total - $active,
            'devices_by_platform' => $byPlatform,
            'activation_rate' => $total > 0 ? round(($active / $total) * 100, 2) : 0
        ];
    }

    /**
     * Get user's active devices.
     */
    public static function getActiveDevicesForUser($userId): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('user_id', $userId)
            ->active()
            ->get();
    }

    /**
     * Register new device token.
     */
    public static function registerDevice($userId, $deviceToken, $platform, $deviceInfo = []): self
    {
        // Deactivate existing tokens for the same user and platform
        static::where('user_id', $userId)
            ->where('platform', $platform)
            ->update(['status' => 'inactive']);

        return static::create([
            'user_id' => $userId,
            'device_token' => $deviceToken,
            'platform' => $platform,
            'app_version' => $deviceInfo['app_version'] ?? null,
            'device_model' => $deviceInfo['device_model'] ?? null,
            'os_version' => $deviceInfo['os_version'] ?? null,
            'status' => 'active',
            'last_used_at' => now(),
            'expires_at' => now()->addYear(), // Default expiry: 1 year
            'preferences' => $deviceInfo['preferences'] ?? null
        ]);
    }

    /**
     * Unregister device token.
     */
    public static function unregisterDevice($userId, $deviceToken): bool
    {
        return static::where('user_id', $userId)
            ->where('device_token', $deviceToken)
            ->update(['status' => 'inactive']);
    }

    /**
     * Clean up expired devices.
     */
    public static function cleanupExpiredDevices(): int
    {
        return static::where('expires_at', '<', now())
            ->orWhere('last_used_at', '<', now()->subMonths(6))
            ->update(['status' => 'expired']);
    }
}
