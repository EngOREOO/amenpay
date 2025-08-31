<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'currency',
        'category_id',
        'description',
        'status',
        'reference_id',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Get the wallet that owns the transaction.
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Get the category of the transaction.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user through wallet.
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Wallet::class,
            'id', // Foreign key on wallets table
            'id', // Foreign key on users table
            'wallet_id', // Local key on transactions table
            'user_id' // Local key on wallets table
        );
    }

    /**
     * Check if transaction is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if transaction is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transaction is failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if transaction is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Mark transaction as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update(['status' => 'completed']);
    }

    /**
     * Mark transaction as failed.
     */
    public function markAsFailed(): void
    {
        $this->update(['status' => 'failed']);
    }

    /**
     * Mark transaction as cancelled.
     */
    public function markAsCancelled(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    /**
     * Generate unique reference ID.
     */
    public static function generateReferenceId(): string
    {
        do {
            $reference = 'TXN' . strtoupper(uniqid());
        } while (static::where('reference_id', $reference)->exists());

        return $reference;
    }
}
