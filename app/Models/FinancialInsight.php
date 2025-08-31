<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class FinancialInsight extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'data',
        'severity',
        'category',
        'is_actionable',
        'actions',
        'insight_date',
        'expires_at',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'actions' => 'array',
        'insight_date' => 'datetime',
        'expires_at' => 'datetime',
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];

    protected $appends = [
        'title',
        'description',
        'is_expired',
        'days_since_insight',
        'priority_score'
    ];

    /**
     * Get the user that owns the insight.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the localized title based on current locale.
     */
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->title_ar : $this->title_en;
    }

    /**
     * Get the localized description based on current locale.
     */
    public function getDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->description_ar : $this->description_en;
    }

    /**
     * Check if insight is expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get days since insight was generated.
     */
    public function getDaysSinceInsightAttribute(): int
    {
        return $this->insight_date->diffInDays(now());
    }

    /**
     * Calculate priority score based on severity and recency.
     */
    public function getPriorityScoreAttribute(): int
    {
        $severityScore = [
            'low' => 1,
            'medium' => 2,
            'high' => 3,
            'critical' => 4
        ][$this->severity] ?? 2;

        $recencyScore = max(1, 10 - $this->days_since_insight);
        
        return $severityScore * $recencyScore;
    }

    /**
     * Mark insight as read.
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Check if insight requires immediate attention.
     */
    public function requiresImmediateAttention(): bool
    {
        return $this->severity === 'critical' && !$this->is_expired;
    }

    /**
     * Get insight summary for display.
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'description' => $this->description,
            'severity' => $this->severity,
            'category' => $this->category,
            'is_actionable' => $this->is_actionable,
            'actions' => $this->actions,
            'priority_score' => $this->priority_score,
            'days_since_insight' => $this->days_since_insight,
            'is_expired' => $this->is_expired
        ];
    }

    /**
     * Scope for active insights.
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope for unread insights.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for insights by severity.
     */
    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope for insights by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for actionable insights.
     */
    public function scopeActionable($query)
    {
        return $query->where('is_actionable', true);
    }

    /**
     * Scope for critical insights.
     */
    public function scopeCritical($query)
    {
        return $query->where('severity', 'critical');
    }

    /**
     * Scope for high priority insights.
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('severity', ['high', 'critical']);
    }

    /**
     * Scope for recent insights.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('insight_date', '>=', now()->subDays($days));
    }

    /**
     * Scope for insights by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get insights statistics for user.
     */
    public static function getStatisticsForUser($userId): array
    {
        $insights = static::where('user_id', $userId);
        
        return [
            'total_insights' => $insights->count(),
            'unread_insights' => $insights->unread()->count(),
            'critical_insights' => $insights->critical()->count(),
            'actionable_insights' => $insights->actionable()->count(),
            'insights_by_category' => $insights->get()->groupBy('category')->map->count(),
            'insights_by_severity' => $insights->get()->groupBy('severity')->map->count(),
            'recent_insights' => $insights->recent(7)->count()
        ];
    }

    /**
     * Generate spending analysis insight.
     */
    public static function generateSpendingAnalysis($user): ?self
    {
        $wallet = $user->wallet;
        if (!$wallet) return null;

        $monthlyExpenses = abs($wallet->transactions()
            ->where('amount', '<', 0)
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('amount'));

        $previousMonthExpenses = abs($wallet->transactions()
            ->where('amount', '<', 0)
            ->where('created_at', '>=', now()->subMonth()->startOfMonth())
            ->where('created_at', '<', now()->startOfMonth())
            ->sum('amount'));

        $change = $previousMonthExpenses > 0 ? 
            (($monthlyExpenses - $previousMonthExpenses) / $previousMonthExpenses) * 100 : 0;

        if (abs($change) > 20) {
            $trend = $change > 0 ? 'increased' : 'decreased';
            $severity = abs($change) > 50 ? 'high' : 'medium';
            
            return static::create([
                'user_id' => $user->id,
                'type' => 'spending_analysis',
                'title_ar' => 'تحليل الإنفاق الشهري',
                'title_en' => 'Monthly Spending Analysis',
                'description_ar' => "إنفاقك الشهري {$trend} بنسبة " . abs(round($change, 1)) . "% مقارنة بالشهر السابق",
                'description_en' => "Your monthly spending has {$trend} by " . abs(round($change, 1)) . "% compared to last month",
                'data' => [
                    'current_month' => $monthlyExpenses,
                    'previous_month' => $previousMonthExpenses,
                    'change_percentage' => $change,
                    'trend' => $trend
                ],
                'severity' => $severity,
                'category' => 'spending',
                'is_actionable' => true,
                'actions' => [
                    'review_expenses' => 'Review your expenses',
                    'set_budget' => 'Set or adjust budget limits',
                    'identify_categories' => 'Identify high-spending categories'
                ],
                'insight_date' => now(),
                'expires_at' => now()->addDays(30)
            ]);
        }

        return null;
    }

    /**
     * Generate savings opportunity insight.
     */
    public static function generateSavingsOpportunity($user): ?self
    {
        $wallet = $user->wallet;
        if (!$wallet) return null;

        $monthlyIncome = $wallet->transactions()
            ->where('amount', '>', 0)
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('amount');

        $monthlyExpenses = abs($wallet->transactions()
            ->where('amount', '<', 0)
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('amount'));

        $savingsRate = $monthlyIncome > 0 ? (($monthlyIncome - $monthlyExpenses) / $monthlyIncome) * 100 : 0;

        if ($savingsRate < 20) {
            $potentialSavings = $monthlyIncome * 0.2 - ($monthlyIncome - $monthlyExpenses);
            
            return static::create([
                'user_id' => $user->id,
                'type' => 'savings_opportunity',
                'title_ar' => 'فرصة توفير',
                'title_en' => 'Savings Opportunity',
                'description_ar' => "يمكنك توفير SAR " . round($potentialSavings, 2) . " شهرياً لتحقيق معدل توفير 20%",
                'description_en' => "You can save SAR " . round($potentialSavings, 2) . " monthly to achieve a 20% savings rate",
                'data' => [
                    'current_savings_rate' => $savingsRate,
                    'target_savings_rate' => 20,
                    'potential_monthly_savings' => $potentialSavings,
                    'monthly_income' => $monthlyIncome,
                    'monthly_expenses' => $monthlyExpenses
                ],
                'severity' => 'medium',
                'category' => 'savings',
                'is_actionable' => true,
                'actions' => [
                    'review_expenses' => 'Review and categorize expenses',
                    'set_savings_goal' => 'Set a monthly savings goal',
                    'automate_savings' => 'Set up automatic savings transfers'
                ],
                'insight_date' => now(),
                'expires_at' => now()->addDays(60)
            ]);
        }

        return null;
    }

    /**
     * Generate risk alert insight.
     */
    public static function generateRiskAlert($user): ?self
    {
        $wallet = $user->wallet;
        if (!$wallet) return null;

        // Check for unusual spending patterns
        $recentTransactions = $wallet->transactions()
            ->where('created_at', '>=', now()->subDays(7))
            ->get();

        $dailySpending = $recentTransactions
            ->where('amount', '<', 0)
            ->groupBy(function ($transaction) {
                return $transaction->created_at->format('Y-m-d');
            })
            ->map(function ($transactions) {
                return abs($transactions->sum('amount'));
            });

        $averageDailySpending = $dailySpending->avg();
        $maxDailySpending = $dailySpending->max();

        if ($maxDailySpending > $averageDailySpending * 3) {
            return static::create([
                'user_id' => $user->id,
                'type' => 'risk_alert',
                'title_ar' => 'تنبيه إنفاق غير عادي',
                'title_en' => 'Unusual Spending Alert',
                'description_ar' => 'تم اكتشاف إنفاق يومي غير عادي. راجع معاملاتك للتأكد من صحتها.',
                'description_en' => 'Unusual daily spending detected. Review your transactions to ensure they are legitimate.',
                'data' => [
                    'average_daily_spending' => $averageDailySpending,
                    'max_daily_spending' => $maxDailySpending,
                    'multiplier' => round($maxDailySpending / $averageDailySpending, 2),
                    'date_of_concern' => $dailySpending->search($maxDailySpending)
                ],
                'severity' => 'high',
                'category' => 'risk',
                'is_actionable' => true,
                'actions' => [
                    'review_transactions' => 'Review recent transactions',
                    'check_cards' => 'Verify card transactions',
                    'contact_support' => 'Contact support if suspicious'
                ],
                'insight_date' => now(),
                'expires_at' => now()->addDays(7)
            ]);
        }

        return null;
    }
}
