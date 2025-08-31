<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_number',
        'balance',
        'currency',
        'status',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    /**
     * Get the user that owns the wallet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wallet's transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Check if wallet is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Add amount to wallet balance.
     */
    public function addBalance(float $amount): void
    {
        $this->increment('balance', $amount);
    }

    /**
     * Subtract amount from wallet balance.
     */
    public function subtractBalance(float $amount): void
    {
        $this->decrement('balance', $amount);
    }

    /**
     * Check if wallet has sufficient balance.
     */
    public function hasSufficientBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }

    /**
     * Generate unique wallet number.
     */
    public static function generateWalletNumber(): string
    {
        do {
            $number = 'W' . str_pad(rand(1, 999999999), 9, '0', STR_PAD_LEFT);
        } while (static::where('wallet_number', $number)->exists());

        return $number;
    }
}
