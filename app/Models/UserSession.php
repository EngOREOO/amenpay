<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'device_info',
        'ip_address',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'device_info' => 'array',
    ];

    public $timestamps = false;

    /**
     * Get the user that owns the session.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if session is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    /**
     * Check if session is valid (not expired).
     */
    public function isValid(): bool
    {
        return !$this->isExpired();
    }

    /**
     * Extend session expiry.
     */
    public function extend(int $minutes = 60): void
    {
        $this->update(['expires_at' => now()->addMinutes($minutes)]);
    }

    /**
     * Create new session for user.
     */
    public static function createForUser(User $user, array $deviceInfo = [], string $ipAddress = null): self
    {
        return static::create([
            'user_id' => $user->id,
            'token' => static::generateToken(),
            'device_info' => $deviceInfo,
            'ip_address' => $ipAddress,
            'expires_at' => now()->addDays(30),
        ]);
    }

    /**
     * Generate unique token.
     */
    public static function generateToken(): string
    {
        do {
            $token = bin2hex(random_bytes(32));
        } while (static::where('token', $token)->exists());

        return $token;
    }

    /**
     * Find session by token.
     */
    public static function findByToken(string $token): ?self
    {
        return static::where('token', $token)
            ->where('expires_at', '>', now())
            ->first();
    }

    /**
     * Clean expired sessions.
     */
    public static function cleanExpired(): int
    {
        return static::where('expires_at', '<', now())->delete();
    }

    /**
     * Scope for valid sessions.
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope for expired sessions.
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }
}
