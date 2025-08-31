<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'content_ar',
        'content_en',
        'type',
        'priority',
        'status',
        'published_at',
        'expires_at',
        'target_audience',
        'delivery_channels',
        'requires_acknowledgment',
        'acknowledged_count',
        'metadata'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'target_audience' => 'array',
        'delivery_channels' => 'array',
        'requires_acknowledgment' => 'boolean',
        'metadata' => 'array'
    ];

    protected $appends = [
        'title',
        'content',
        'is_published',
        'is_expired',
        'is_active',
        'days_since_published',
        'days_until_expiry'
    ];

    /**
     * Get the localized title based on current locale.
     */
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->title_ar : $this->title_en;
    }

    /**
     * Get the localized content based on current locale.
     */
    public function getContentAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->content_ar : $this->content_en;
    }

    /**
     * Check if announcement is published.
     */
    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'active' && $this->published_at && $this->published_at->isPast();
    }

    /**
     * Check if announcement is expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if announcement is active.
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->is_published && !$this->is_expired;
    }

    /**
     * Get days since published.
     */
    public function getDaysSincePublishedAttribute(): ?int
    {
        return $this->published_at ? $this->published_at->diffInDays(now()) : null;
    }

    /**
     * Get days until expiry.
     */
    public function getDaysUntilExpiryAttribute(): ?int
    {
        return $this->expires_at ? $this->expires_at->diffInDays(now()) : null;
    }

    /**
     * Get notifications for this announcement.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Publish the announcement.
     */
    public function publish(): void
    {
        $this->update([
            'status' => 'active',
            'published_at' => now()
        ]);
    }

    /**
     * Unpublish the announcement.
     */
    public function unpublish(): void
    {
        $this->update(['status' => 'draft']);
    }

    /**
     * Archive the announcement.
     */
    public function archive(): void
    {
        $this->update(['status' => 'archived']);
    }

    /**
     * Schedule the announcement.
     */
    public function schedule(Carbon $publishDate): void
    {
        $this->update([
            'status' => 'scheduled',
            'published_at' => $publishDate
        ]);
    }

    /**
     * Check if announcement targets specific user.
     */
    public function targetsUser($user): bool
    {
        if (!$this->target_audience) {
            return true; // No targeting means all users
        }

        $audience = $this->target_audience;

        // Check language targeting
        if (isset($audience['languages']) && !in_array($user->language, $audience['languages'])) {
            return false;
        }

        // Check user status targeting
        if (isset($audience['user_statuses']) && !in_array($user->status, $audience['user_statuses'])) {
            return false;
        }

        // Check user type targeting
        if (isset($audience['user_types'])) {
            $userType = $this->getUserType($user);
            if (!in_array($userType, $audience['user_types'])) {
                return false;
            }
        }

        // Check registration date targeting
        if (isset($audience['registration_date_range'])) {
            $range = $audience['registration_date_range'];
            $userRegistrationDate = $user->created_at;
            
            if (isset($range['after']) && $userRegistrationDate->lt(Carbon::parse($range['after']))) {
                return false;
            }
            
            if (isset($range['before']) && $userRegistrationDate->gt(Carbon::parse($range['before']))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get user type for targeting.
     */
    private function getUserType($user): string
    {
        $registrationDays = $user->created_at->diffInDays(now());
        
        if ($registrationDays < 7) return 'new';
        if ($registrationDays < 30) return 'recent';
        if ($registrationDays < 90) return 'active';
        return 'established';
    }

    /**
     * Check if announcement should be delivered via specific channel.
     */
    public function shouldDeliverVia(string $channel): bool
    {
        if (!$this->delivery_channels) {
            return true; // No channel restriction means all channels
        }

        return in_array($channel, $this->delivery_channels);
    }

    /**
     * Increment acknowledgment count.
     */
    public function incrementAcknowledgment(): void
    {
        $this->increment('acknowledged_count');
    }

    /**
     * Get announcement summary.
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'priority' => $this->priority,
            'status' => $this->status,
            'is_published' => $this->is_published,
            'is_expired' => $this->is_expired,
            'days_since_published' => $this->days_since_published,
            'days_until_expiry' => $this->days_until_expiry,
            'requires_acknowledgment' => $this->requires_acknowledgment,
            'acknowledged_count' => $this->acknowledged_count
        ];
    }

    /**
     * Scope for published announcements.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'active')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope for active announcements.
     */
    public function scopeActive($query)
    {
        return $query->published()
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope for announcements by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for announcements by priority.
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for announcements by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for urgent announcements.
     */
    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }

    /**
     * Scope for high priority announcements.
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }

    /**
     * Scope for announcements requiring acknowledgment.
     */
    public function scopeRequiringAcknowledgment($query)
    {
        return $query->where('requires_acknowledgment', true);
    }

    /**
     * Scope for announcements by delivery channel.
     */
    public function scopeByDeliveryChannel($query, $channel)
    {
        return $query->whereJsonContains('delivery_channels', $channel);
    }

    /**
     * Get announcement statistics.
     */
    public static function getStatistics(): array
    {
        $total = static::count();
        $active = static::active()->count();
        $draft = static::where('status', 'draft')->count();
        $scheduled = static::where('status', 'scheduled')->count();
        $archived = static::where('status', 'archived')->count();

        $byType = static::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        $byPriority = static::selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();

        return [
            'total_announcements' => $total,
            'active_announcements' => $active,
            'draft_announcements' => $draft,
            'scheduled_announcements' => $scheduled,
            'archived_announcements' => $archived,
            'announcements_by_type' => $byType,
            'announcements_by_priority' => $byPriority,
            'publication_rate' => $total > 0 ? round(($active / $total) * 100, 2) : 0
        ];
    }

    /**
     * Get active announcements for user.
     */
    public static function getActiveForUser($user): \Illuminate\Database\Eloquent\Collection
    {
        return static::active()
            ->get()
            ->filter(function ($announcement) use ($user) {
                return $announcement->targetsUser($user);
            });
    }

    /**
     * Create system announcement.
     */
    public static function createSystemAnnouncement(
        string $titleAr,
        string $titleEn,
        string $contentAr,
        string $contentEn,
        string $type = 'info',
        string $priority = 'medium',
        array $deliveryChannels = ['push', 'in_app'],
        bool $requiresAcknowledgment = false
    ): self {
        return static::create([
            'title_ar' => $titleAr,
            'title_en' => $titleEn,
            'content_ar' => $contentAr,
            'content_en' => $contentEn,
            'type' => $type,
            'priority' => $priority,
            'status' => 'active',
            'published_at' => now(),
            'delivery_channels' => $deliveryChannels,
            'requires_acknowledgment' => $requiresAcknowledgment
        ]);
    }

    /**
     * Create maintenance announcement.
     */
    public static function createMaintenanceAnnouncement(
        string $titleAr,
        string $titleEn,
        string $contentAr,
        string $contentEn,
        Carbon $startTime,
        Carbon $endTime,
        string $priority = 'high'
    ): self {
        return static::create([
            'title_ar' => $titleAr,
            'title_en' => $titleEn,
            'content_ar' => $contentAr,
            'content_en' => $contentEn,
            'type' => 'maintenance',
            'priority' => $priority,
            'status' => 'active',
            'published_at' => $startTime,
            'expires_at' => $endTime,
            'delivery_channels' => ['push', 'sms', 'email', 'in_app'],
            'requires_acknowledgment' => true
        ]);
    }

    /**
     * Clean up expired announcements.
     */
    public static function cleanupExpired(): int
    {
        return static::where('expires_at', '<', now())
            ->where('status', 'active')
            ->update(['status' => 'expired']);
    }
}
