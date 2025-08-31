<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'target_amount',
        'current_amount',
        'progress_percentage',
        'type',
        'priority',
        'target_date',
        'status',
        'icon',
        'color',
        'milestones',
        'notifications'
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'progress_percentage' => 'decimal:2',
        'target_date' => 'date',
        'milestones' => 'array',
        'notifications' => 'array'
    ];

    protected $appends = [
        'name',
        'description',
        'is_completed',
        'is_overdue',
        'days_remaining',
        'monthly_savings_needed',
        'weekly_savings_needed',
        'daily_savings_needed'
    ];

    /**
     * Get the user that owns the goal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
     * Get the localized description based on current locale.
     */
    public function getDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->description_ar : $this->description_en;
    }

    /**
     * Check if goal is completed.
     */
    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed' || $this->current_amount >= $this->target_amount;
    }

    /**
     * Check if goal is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->target_date && $this->target_date->isPast() && !$this->is_completed;
    }

    /**
     * Get days remaining until target date.
     */
    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->target_date) return null;
        return max(0, $this->target_date->diffInDays(now()));
    }

    /**
     * Calculate monthly savings needed to reach goal.
     */
    public function getMonthlySavingsNeededAttribute(): float
    {
        if ($this->is_completed) return 0;
        
        $remaining = $this->target_amount - $this->current_amount;
        if ($remaining <= 0) return 0;
        
        if (!$this->target_date) return $remaining; // No target date, suggest monthly
        
        $monthsRemaining = max(1, $this->target_date->diffInMonths(now()));
        return round($remaining / $monthsRemaining, 2);
    }

    /**
     * Calculate weekly savings needed to reach goal.
     */
    public function getWeeklySavingsNeededAttribute(): float
    {
        if ($this->is_completed) return 0;
        
        $remaining = $this->target_amount - $this->current_amount;
        if ($remaining <= 0) return 0;
        
        if (!$this->target_date) return round($remaining / 52, 2); // Assume 1 year
        
        $weeksRemaining = max(1, $this->target_date->diffInWeeks(now()));
        return round($remaining / $weeksRemaining, 2);
    }

    /**
     * Calculate daily savings needed to reach goal.
     */
    public function getDailySavingsNeededAttribute(): float
    {
        if ($this->is_completed) return 0;
        
        $remaining = $this->target_amount - $this->current_amount;
        if ($remaining <= 0) return 0;
        
        if (!$this->target_date) return round($remaining / 365, 2); // Assume 1 year
        
        $daysRemaining = max(1, $this->target_date->diffInDays(now()));
        return round($remaining / $daysRemaining, 2);
    }

    /**
     * Add amount to current goal progress.
     */
    public function addAmount(float $amount): void
    {
        $this->increment('current_amount', $amount);
        $this->updateProgress();
    }

    /**
     * Update goal progress percentage.
     */
    public function updateProgress(): void
    {
        if ($this->target_amount <= 0) {
            $this->update(['progress_percentage' => 0]);
            return;
        }

        $percentage = round(($this->current_amount / $this->target_amount) * 100, 2);
        $this->update(['progress_percentage' => min(100, $percentage)]);

        // Check if goal is completed
        if ($this->current_amount >= $this->target_amount && $this->status !== 'completed') {
            $this->complete();
        }
    }

    /**
     * Mark goal as completed.
     */
    public function complete(): void
    {
        $this->update([
            'status' => 'completed',
            'progress_percentage' => 100,
            'completed_at' => now()
        ]);

        // Send completion notification
        $this->sendCompletionNotification();
    }

    /**
     * Pause goal.
     */
    public function pause(): void
    {
        $this->update(['status' => 'paused']);
    }

    /**
     * Resume paused goal.
     */
    public function resume(): void
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Cancel goal.
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    /**
     * Extend target date.
     */
    public function extendTargetDate(Carbon $newTargetDate): void
    {
        $this->update(['target_date' => $newTargetDate]);
        $this->updateProgress();
    }

    /**
     * Add milestone to goal.
     */
    public function addMilestone(string $title, float $amount, ?string $description = null): void
    {
        $milestones = $this->milestones ?? [];
        $milestones[] = [
            'id' => uniqid(),
            'title' => $title,
            'amount' => $amount,
            'description' => $description,
            'created_at' => now()->toISOString(),
            'is_achieved' => false
        ];

        $this->update(['milestones' => $milestones]);
    }

    /**
     * Mark milestone as achieved.
     */
    public function achieveMilestone(string $milestoneId): void
    {
        $milestones = $this->milestones ?? [];
        
        foreach ($milestones as &$milestone) {
            if ($milestone['id'] === $milestoneId) {
                $milestone['is_achieved'] = true;
                $milestone['achieved_at'] = now()->toISOString();
                break;
            }
        }

        $this->update(['milestones' => $milestones]);
    }

    /**
     * Get goal statistics.
     */
    public function getStatistics(): array
    {
        return [
            'total_goals' => $this->user->goals()->count(),
            'active_goals' => $this->user->goals()->where('status', 'active')->count(),
            'completed_goals' => $this->user->goals()->where('status', 'completed')->count(),
            'paused_goals' => $this->user->goals()->where('status', 'paused')->count(),
            'overdue_goals' => $this->user->goals()->where('status', 'active')->where('target_date', '<', now())->count(),
            'total_target_amount' => $this->user->goals()->sum('target_amount'),
            'total_current_amount' => $this->user->goals()->sum('current_amount'),
            'total_progress' => $this->user->goals()->avg('progress_percentage')
        ];
    }

    /**
     * Send completion notification.
     */
    private function sendCompletionNotification(): void
    {
        // In a real application, you would send a notification here
        // For now, we'll just log it
        \Log::info("Goal completed: {$this->name} by user {$this->user->id}");
    }

    /**
     * Scope for active goals.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for completed goals.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for paused goals.
     */
    public function scopePaused($query)
    {
        return $query->where('status', 'paused');
    }

    /**
     * Scope for goals by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for goals by priority.
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for overdue goals.
     */
    public function scopeOverdue($query)
    {
        return $query->where('target_date', '<', now())
                    ->where('status', 'active');
    }

    /**
     * Scope for goals with target date.
     */
    public function scopeWithTargetDate($query)
    {
        return $query->whereNotNull('target_date');
    }
}
