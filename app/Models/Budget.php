<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'name_ar',
        'name_en',
        'amount',
        'spent',
        'remaining',
        'period',
        'start_date',
        'end_date',
        'status',
        'notifications'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'spent' => 'decimal:2',
        'remaining' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'notifications' => 'array'
    ];

    protected $appends = [
        'name',
        'is_overdue',
        'is_completed',
        'spent_percentage',
        'days_remaining',
        'daily_budget'
    ];

    /**
     * Get the user that owns the budget.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category associated with the budget.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the transactions that contribute to this budget.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'category_id', 'category_id')
            ->where('wallet_id', $this->user->wallet->id)
            ->where('amount', '<', 0) // Only expenses
            ->whereBetween('created_at', [$this->start_date, $this->end_date]);
    }

    /**
     * Get the localized name based on current locale.
     */
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->name_ar : $this->name_en;
    }

    /**
     * Check if budget is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->end_date->isPast() && $this->status === 'active';
    }

    /**
     * Check if budget is completed.
     */
    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Get spent percentage.
     */
    public function getSpentPercentageAttribute(): float
    {
        if ($this->amount <= 0) return 0;
        return round(($this->spent / $this->amount) * 100, 2);
    }

    /**
     * Get days remaining.
     */
    public function getDaysRemainingAttribute(): int
    {
        return max(0, $this->end_date->diffInDays(now()));
    }

    /**
     * Get daily budget allocation.
     */
    public function getDailyBudgetAttribute(): float
    {
        $days = $this->start_date->diffInDays($this->end_date) + 1;
        return $days > 0 ? round($this->amount / $days, 2) : 0;
    }

    /**
     * Update budget spent amount based on transactions.
     */
    public function updateSpentAmount(): void
    {
        $spent = $this->transactions()->sum('amount');
        $this->update([
            'spent' => abs($spent),
            'remaining' => $this->amount - abs($spent)
        ]);

        // Update status based on spent amount
        $this->updateStatus();
    }

    /**
     * Update budget status based on current state.
     */
    public function updateStatus(): void
    {
        $newStatus = $this->status;

        if ($this->spent >= $this->amount) {
            $newStatus = 'completed';
        } elseif ($this->end_date->isPast() && $this->status === 'active') {
            $newStatus = 'overdue';
        }

        if ($newStatus !== $this->status) {
            $this->update(['status' => $newStatus]);
        }
    }

    /**
     * Check if budget is within limits.
     */
    public function isWithinLimits(): bool
    {
        return $this->spent < $this->amount;
    }

    /**
     * Get budget warning level.
     */
    public function getWarningLevel(): string
    {
        $percentage = $this->spent_percentage;
        
        if ($percentage >= 90) return 'critical';
        if ($percentage >= 75) return 'warning';
        if ($percentage >= 50) return 'notice';
        
        return 'safe';
    }

    /**
     * Extend budget end date.
     */
    public function extendEndDate(Carbon $newEndDate): void
    {
        $this->update(['end_date' => $newEndDate]);
        $this->updateSpentAmount();
    }

    /**
     * Reset budget for new period.
     */
    public function resetForNewPeriod(): void
    {
        $oldEndDate = $this->end_date;
        
        // Calculate new period dates
        switch ($this->period) {
            case 'daily':
                $newStartDate = $oldEndDate->addDay();
                $newEndDate = $newStartDate->copy();
                break;
            case 'weekly':
                $newStartDate = $oldEndDate->addWeek();
                $newEndDate = $newStartDate->copy()->addWeek()->subDay();
                break;
            case 'monthly':
                $newStartDate = $oldEndDate->addMonth();
                $newEndDate = $newStartDate->copy()->endOfMonth();
                break;
            case 'yearly':
                $newStartDate = $oldEndDate->addYear();
                $newEndDate = $newStartDate->copy()->endOfYear();
                break;
            default:
                return;
        }

        // Create new budget record
        $this->create([
            'user_id' => $this->user_id,
            'category_id' => $this->category_id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'amount' => $this->amount,
            'period' => $this->period,
            'start_date' => $newStartDate,
            'end_date' => $newEndDate,
            'notifications' => $this->notifications
        ]);

        // Mark current budget as completed
        $this->update(['status' => 'completed']);
    }

    /**
     * Get budget statistics.
     */
    public function getStatistics(): array
    {
        return [
            'total_budgets' => $this->user->budgets()->count(),
            'active_budgets' => $this->user->budgets()->where('status', 'active')->count(),
            'completed_budgets' => $this->user->budgets()->where('status', 'completed')->count(),
            'overdue_budgets' => $this->user->budgets()->where('status', 'overdue')->count(),
            'total_budget_amount' => $this->user->budgets()->sum('amount'),
            'total_spent' => $this->user->budgets()->sum('spent'),
            'total_saved' => $this->user->budgets()->sum('remaining')
        ];
    }

    /**
     * Scope for active budgets.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for overdue budgets.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    /**
     * Scope for completed budgets.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for budgets by period.
     */
    public function scopeByPeriod($query, $period)
    {
        return $query->where('period', $period);
    }

    /**
     * Scope for budgets by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
