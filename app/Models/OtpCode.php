<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'code',
        'type',
        'expires_at',
        'is_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    public $timestamps = false;

    /**
     * Check if OTP is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    /**
     * Check if OTP is used.
     */
    public function isUsed(): bool
    {
        return $this->is_used;
    }

    /**
     * Check if OTP is valid (not expired and not used).
     */
    public function isValid(): bool
    {
        return !$this->isExpired() && !$this->isUsed();
    }

    /**
     * Mark OTP as used.
     */
    public function markAsUsed(): void
    {
        $this->update(['is_used' => true]);
    }

    /**
     * Generate OTP code.
     */
    public static function generateCode(): string
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create new OTP for phone.
     */
    public static function createForPhone(string $phone, string $type = 'login'): self
    {
        // Invalidate previous OTPs for this phone and type
        static::where('phone', $phone)
            ->where('type', $type)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        return static::create([
            'phone' => $phone,
            'code' => static::generateCode(),
            'type' => $type,
            'expires_at' => now()->addMinutes(5),
            'is_used' => false,
        ]);
    }

    /**
     * Verify OTP code.
     */
    public static function verify(string $phone, string $code, string $type = 'login'): bool
    {
        $otp = static::where('phone', $phone)
            ->where('code', $code)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if ($otp) {
            $otp->markAsUsed();
            return true;
        }

        return false;
    }

    /**
     * Scope for valid OTPs.
     */
    public function scopeValid($query)
    {
        return $query->where('is_used', false)
                    ->where('expires_at', '>', now());
    }
}
