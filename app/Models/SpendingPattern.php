<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class SpendingPattern extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'pattern_type',
        'pattern_data',
        'confidence_score',
        'predictions',
        'anomalies',
        'is_active',
        'last_updated'
    ];

    protected $casts = [
        'pattern_data' => 'array',
        'predictions' => 'array',
        'anomalies' => 'array',
        'confidence_score' => 'decimal:2',
        'is_active' => 'boolean',
        'last_updated' => 'datetime'
    ];

    protected $appends = [
        'pattern_summary',
        'next_prediction',
        'anomaly_count',
        'trend_direction'
    ];

    /**
     * Get the user that owns the pattern.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category associated with the pattern.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get pattern summary for display.
     */
    public function getPatternSummaryAttribute(): array
    {
        $data = $this->pattern_data ?? [];
        
        return [
            'type' => $this->pattern_type,
            'category' => $this->category?->name ?? 'General',
            'confidence' => $this->confidence_score,
            'frequency' => $data['frequency'] ?? 'unknown',
            'average_amount' => $data['average_amount'] ?? 0,
            'total_occurrences' => $data['total_occurrences'] ?? 0,
            'last_occurrence' => $data['last_occurrence'] ?? null
        ];
    }

    /**
     * Get next predicted occurrence.
     */
    public function getNextPredictionAttribute(): ?array
    {
        $predictions = $this->predictions ?? [];
        return $predictions['next_occurrence'] ?? null;
    }

    /**
     * Get count of detected anomalies.
     */
    public function getAnomalyCountAttribute(): int
    {
        $anomalies = $this->anomalies ?? [];
        return count($anomalies);
    }

    /**
     * Get trend direction.
     */
    public function getTrendDirectionAttribute(): string
    {
        $data = $this->pattern_data ?? [];
        $trend = $data['trend'] ?? 'stable';
        
        return match($trend) {
            'increasing' => 'up',
            'decreasing' => 'down',
            'fluctuating' => 'variable',
            default => 'stable'
        };
    }

    /**
     * Update pattern data and confidence score.
     */
    public function updatePattern(array $newData, float $newConfidence): void
    {
        $this->update([
            'pattern_data' => $newData,
            'confidence_score' => $newConfidence,
            'last_updated' => now()
        ]);
    }

    /**
     * Add new prediction.
     */
    public function addPrediction(array $prediction): void
    {
        $predictions = $this->predictions ?? [];
        $predictions[] = $prediction;
        
        $this->update(['predictions' => $predictions]);
    }

    /**
     * Add new anomaly.
     */
    public function addAnomaly(array $anomaly): void
    {
        $anomalies = $this->anomalies ?? [];
        $anomalies[] = $anomaly;
        
        $this->update(['anomalies' => $anomalies]);
    }

    /**
     * Check if pattern is reliable.
     */
    public function isReliable(): bool
    {
        return $this->confidence_score >= 70;
    }

    /**
     * Get pattern strength level.
     */
    public function getStrengthLevel(): string
    {
        if ($this->confidence_score >= 90) return 'very_strong';
        if ($this->confidence_score >= 80) return 'strong';
        if ($this->confidence_score >= 70) return 'moderate';
        if ($this->confidence_score >= 50) return 'weak';
        return 'very_weak';
    }

    /**
     * Scope for active patterns.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for reliable patterns.
     */
    public function scopeReliable($query)
    {
        return $query->where('confidence_score', '>=', 70);
    }

    /**
     * Scope for patterns by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('pattern_type', $type);
    }

    /**
     * Scope for patterns by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope for high confidence patterns.
     */
    public function scopeHighConfidence($query)
    {
        return $query->where('confidence_score', '>=', 80);
    }

    /**
     * Analyze daily spending patterns.
     */
    public static function analyzeDailyPatterns($user): array
    {
        $wallet = $user->wallet;
        if (!$wallet) return [];

        $transactions = $wallet->transactions()
            ->where('amount', '<', 0)
            ->where('created_at', '>=', now()->subDays(30))
            ->get();

        $dailySpending = $transactions->groupBy(function ($transaction) {
            return $transaction->created_at->format('Y-m-d');
        })->map(function ($transactions) {
            return abs($transactions->sum('amount'));
        });

        $averageDaily = $dailySpending->avg();
        $standardDeviation = $this->calculateStandardDeviation($dailySpending->values());

        return [
            'pattern_type' => 'daily',
            'pattern_data' => [
                'average_amount' => $averageDaily,
                'standard_deviation' => $standardDeviation,
                'total_occurrences' => $dailySpending->count(),
                'frequency' => 'daily',
                'trend' => $this->calculateTrend($dailySpending->values()),
                'peak_days' => $this->findPeakDays($dailySpending),
                'low_days' => $this->findLowDays($dailySpending)
            ],
            'confidence_score' => $this->calculateConfidence($dailySpending->count(), $standardDeviation, $averageDaily)
        ];
    }

    /**
     * Analyze weekly spending patterns.
     */
    public static function analyzeWeeklyPatterns($user): array
    {
        $wallet = $user->wallet;
        if (!$wallet) return [];

        $transactions = $wallet->transactions()
            ->where('amount', '<', 0)
            ->where('created_at', '>=', now()->subWeeks(12))
            ->get();

        $weeklySpending = $transactions->groupBy(function ($transaction) {
            return $transaction->created_at->format('Y-W');
        })->map(function ($transactions) {
            return abs($transactions->sum('amount'));
        });

        $averageWeekly = $weeklySpending->avg();
        $standardDeviation = $this->calculateStandardDeviation($weeklySpending->values());

        return [
            'pattern_type' => 'weekly',
            'pattern_data' => [
                'average_amount' => $averageWeekly,
                'standard_deviation' => $standardDeviation,
                'total_occurrences' => $weeklySpending->count(),
                'frequency' => 'weekly',
                'trend' => $this->calculateTrend($weeklySpending->values()),
                'weekday_patterns' => $this->analyzeWeekdayPatterns($transactions)
            ],
            'confidence_score' => $this->calculateConfidence($weeklySpending->count(), $standardDeviation, $averageWeekly)
        ];
    }

    /**
     * Analyze monthly spending patterns.
     */
    public static function analyzeMonthlyPatterns($user): array
    {
        $wallet = $user->wallet;
        if (!$wallet) return [];

        $transactions = $wallet->transactions()
            ->where('amount', '<', 0)
            ->where('created_at', '>=', now()->subMonths(12))
            ->get();

        $monthlySpending = $transactions->groupBy(function ($transaction) {
            return $transaction->created_at->format('Y-m');
        })->map(function ($transactions) {
            return abs($transactions->sum('amount'));
        });

        $averageMonthly = $monthlySpending->avg();
        $standardDeviation = $this->calculateStandardDeviation($monthlySpending->values());

        return [
            'pattern_type' => 'monthly',
            'pattern_data' => [
                'average_amount' => $averageMonthly,
                'standard_deviation' => $standardDeviation,
                'total_occurrences' => $monthlySpending->count(),
                'frequency' => 'monthly',
                'trend' => $this->calculateTrend($monthlySpending->values()),
                'seasonal_patterns' => $this->analyzeSeasonalPatterns($monthlySpending)
            ],
            'confidence_score' => $this->calculateConfidence($monthlySpending->count(), $standardDeviation, $averageMonthly)
        ];
    }

    /**
     * Calculate standard deviation.
     */
    private static function calculateStandardDeviation($values): float
    {
        $count = count($values);
        if ($count === 0) return 0;

        $mean = array_sum($values) / $count;
        $variance = array_sum(array_map(function ($value) use ($mean) {
            return pow($value - $mean, 2);
        }, $values)) / $count;

        return sqrt($variance);
    }

    /**
     * Calculate trend direction.
     */
    private static function calculateTrend($values): string
    {
        $count = count($values);
        if ($count < 2) return 'stable';

        $firstHalf = array_slice($values, 0, floor($count / 2));
        $secondHalf = array_slice($values, floor($count / 2));

        $firstHalfAvg = array_sum($firstHalf) / count($firstHalf);
        $secondHalfAvg = array_sum($secondHalf) / count($secondHalf);

        $change = (($secondHalfAvg - $firstHalfAvg) / $firstHalfAvg) * 100;

        if (abs($change) < 5) return 'stable';
        if ($change > 5) return 'increasing';
        return 'decreasing';
    }

    /**
     * Calculate confidence score.
     */
    private static function calculateConfidence(int $occurrences, float $standardDeviation, float $average): float
    {
        if ($average === 0) return 0;

        $coefficientOfVariation = $standardDeviation / $average;
        $occurrenceScore = min(100, $occurrences * 10);
        $variabilityScore = max(0, 100 - ($coefficientOfVariation * 100));

        return round(($occurrenceScore + $variabilityScore) / 2, 2);
    }

    /**
     * Find peak spending days.
     */
    private static function findPeakDays($dailySpending): array
    {
        $average = $dailySpending->avg();
        $threshold = $average * 1.5;

        return $dailySpending->filter(function ($amount) use ($threshold) {
            return $amount > $threshold;
        })->keys()->toArray();
    }

    /**
     * Find low spending days.
     */
    private static function findLowDays($dailySpending): array
    {
        $average = $dailySpending->avg();
        $threshold = $average * 0.5;

        return $dailySpending->filter(function ($amount) use ($threshold) {
            return $amount < $threshold;
        })->keys()->toArray();
    }

    /**
     * Analyze weekday spending patterns.
     */
    private static function analyzeWeekdayPatterns($transactions): array
    {
        $weekdaySpending = $transactions->groupBy(function ($transaction) {
            return $transaction->created_at->format('l');
        })->map(function ($transactions) {
            return abs($transactions->sum('amount'));
        });

        return $weekdaySpending->toArray();
    }

    /**
     * Analyze seasonal spending patterns.
     */
    private static function analyzeSeasonalPatterns($monthlySpending): array
    {
        $seasonalData = [];
        
        foreach ($monthlySpending as $month => $amount) {
            $monthNum = (int) substr($month, -2);
            $season = match(true) {
                in_array($monthNum, [12, 1, 2]) => 'winter',
                in_array($monthNum, [3, 4, 5]) => 'spring',
                in_array($monthNum, [6, 7, 8]) => 'summer',
                default => 'autumn'
            };

            if (!isset($seasonalData[$season])) {
                $seasonalData[$season] = ['total' => 0, 'count' => 0];
            }

            $seasonalData[$season]['total'] += $amount;
            $seasonalData[$season]['count']++;
        }

        // Calculate averages
        foreach ($seasonalData as $season => $data) {
            $seasonalData[$season]['average'] = $data['total'] / $data['count'];
        }

        return $seasonalData;
    }

    /**
     * Generate spending predictions.
     */
    public function generatePredictions(): array
    {
        $data = $this->pattern_data ?? [];
        $predictions = [];

        switch ($this->pattern_type) {
            case 'daily':
                $predictions = $this->predictDailySpending($data);
                break;
            case 'weekly':
                $predictions = $this->predictWeeklySpending($data);
                break;
            case 'monthly':
                $predictions = $this->predictMonthlySpending($data);
                break;
        }

        $this->update(['predictions' => $predictions]);
        return $predictions;
    }

    /**
     * Predict daily spending.
     */
    private function predictDailySpending(array $data): array
    {
        $average = $data['average_amount'] ?? 0;
        $trend = $data['trend'] ?? 'stable';
        
        $predictions = [];
        for ($i = 1; $i <= 7; $i++) {
            $date = now()->addDays($i);
            $predictedAmount = $average;
            
            // Apply trend
            if ($trend === 'increasing') {
                $predictedAmount *= (1 + ($i * 0.02)); // 2% increase per day
            } elseif ($trend === 'decreasing') {
                $predictedAmount *= (1 - ($i * 0.02)); // 2% decrease per day
            }

            $predictions[] = [
                'date' => $date->format('Y-m-d'),
                'predicted_amount' => round($predictedAmount, 2),
                'confidence' => $this->confidence_score,
                'type' => 'daily_prediction'
            ];
        }

        return $predictions;
    }

    /**
     * Predict weekly spending.
     */
    private function predictWeeklySpending(array $data): array
    {
        $average = $data['average_amount'] ?? 0;
        $trend = $data['trend'] ?? 'stable';
        
        $predictions = [];
        for ($i = 1; $i <= 4; $i++) {
            $weekStart = now()->addWeeks($i)->startOfWeek();
            $predictedAmount = $average;
            
            // Apply trend
            if ($trend === 'increasing') {
                $predictedAmount *= (1 + ($i * 0.05)); // 5% increase per week
            } elseif ($trend === 'decreasing') {
                $predictedAmount *= (1 - ($i * 0.05)); // 5% decrease per week
            }

            $predictions[] = [
                'week_start' => $weekStart->format('Y-m-d'),
                'predicted_amount' => round($predictedAmount, 2),
                'confidence' => $this->confidence_score,
                'type' => 'weekly_prediction'
            ];
        }

        return $predictions;
    }

    /**
     * Predict monthly spending.
     */
    private function predictMonthlySpending(array $data): array
    {
        $average = $data['average_amount'] ?? 0;
        $trend = $data['trend'] ?? 'stable';
        
        $predictions = [];
        for ($i = 1; $i <= 3; $i++) {
            $monthStart = now()->addMonths($i)->startOfMonth();
            $predictedAmount = $average;
            
            // Apply trend
            if ($trend === 'increasing') {
                $predictedAmount *= (1 + ($i * 0.1)); // 10% increase per month
            } elseif ($trend === 'decreasing') {
                $predictedAmount *= (1 - ($i * 0.1)); // 10% decrease per month
            }

            $predictions[] = [
                'month_start' => $monthStart->format('Y-m-d'),
                'predicted_amount' => round($predictedAmount, 2),
                'confidence' => $this->confidence_score,
                'type' => 'monthly_prediction'
            ];
        }

        return $predictions;
    }
}
