<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'phone',
        'email',
        'name',
        'national_id',
        'avatar',
        'language',
        'is_verified',
        'email_verified_at',
        'phone_verified_at',
        'last_login_at',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_verified' => 'boolean',
        ];
    }

    /**
     * Get the user's wallet.
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * Get the user's cards.
     */
    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    /**
     * Get the user's notifications.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the user's sessions.
     */
    public function sessions()
    {
        return $this->hasMany(UserSession::class);
    }

    /**
     * Get the user's transactions through wallet.
     */
    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Wallet::class);
    }

    /**
     * Get the user's goals.
     */
    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }

    /**
     * Get the user's budgets.
     */
    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    /**
     * Get the user's financial insights.
     */
    public function financialInsights(): HasMany
    {
        return $this->hasMany(FinancialInsight::class);
    }

    /**
     * Get the user's spending patterns.
     */
    public function spendingPatterns(): HasMany
    {
        return $this->hasMany(SpendingPattern::class);
    }

    /**
     * Get the user's push notification devices.
     */
    public function pushNotificationDevices(): HasMany
    {
        return $this->hasMany(PushNotification::class);
    }

    /**
     * Get the user's announcements.
     */
    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if user is verified.
     */
    public function isVerified(): bool
    {
        return $this->is_verified;
    }
}
