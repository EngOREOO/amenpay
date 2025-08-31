<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinancialInsight;
use App\Models\SpendingPattern;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Get comprehensive financial analytics dashboard.
     */
    public function dashboard(Request $request): JsonResponse
    {
        $user = $request->user();
        $wallet = $user->wallet;
        
        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'User has no wallet'
            ], 404);
        }

        $period = $request->get('period', 'monthly');
        $startDate = $this->getStartDate($period);
        $endDate = now();

        // Basic financial metrics
        $metrics = $this->getFinancialMetrics($wallet, $startDate, $endDate);
        
        // Spending patterns
        $patterns = $this->getSpendingPatterns($user);
        
        // Financial insights
        $insights = $this->getFinancialInsights($user);
        
        // Predictive analytics
        $predictions = $this->getPredictions($user);
        
        // Category analysis
        $categoryAnalysis = $this->getCategoryAnalysis($wallet, $startDate, $endDate);
        
        // Trend analysis
        $trends = $this->getTrendAnalysis($wallet, $startDate, $endDate);

        return response()->json([
            'success' => true,
            'data' => [
                'period' => $period,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'metrics' => $metrics,
                'patterns' => $patterns,
                'insights' => $insights,
                'predictions' => $predictions,
                'category_analysis' => $categoryAnalysis,
                'trends' => $trends
            ]
        ]);
    }

    /**
     * Get AI-powered financial insights.
     */
    public function insights(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $query = FinancialInsight::where('user_id', $user->id);

        // Filter by category
        if ($request->category) {
            $query->where('category', $request->category);
        }

        // Filter by severity
        if ($request->severity) {
            $query->where('severity', $request->severity);
        }

        // Filter by type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Filter by actionable
        if ($request->actionable !== null) {
            $query->where('is_actionable', $request->actionable);
        }

        $insights = $query->orderBy('priority_score', 'desc')
            ->orderBy('insight_date', 'desc')
            ->paginate($request->per_page ?? 20);

        // Generate new insights if needed
        $this->generateInsights($user);

        return response()->json([
            'success' => true,
            'data' => $insights
        ]);
    }

    /**
     * Get spending pattern analysis.
     */
    public function patterns(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $query = SpendingPattern::where('user_id', $user->id);

        // Filter by pattern type
        if ($request->pattern_type) {
            $query->where('pattern_type', $request->pattern_type);
        }

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by confidence
        if ($request->min_confidence) {
            $query->where('confidence_score', '>=', $request->min_confidence);
        }

        $patterns = $query->orderBy('confidence_score', 'desc')
            ->orderBy('last_updated', 'desc')
            ->paginate($request->per_page ?? 20);

        // Analyze patterns if needed
        $this->analyzePatterns($user);

        return response()->json([
            'success' => true,
            'data' => $patterns
        ]);
    }

    /**
     * Get predictive analytics.
     */
    public function predictions(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $predictions = [];
        
        // Get spending predictions
        $spendingPatterns = $user->spendingPatterns()
            ->active()
            ->reliable()
            ->get();

        foreach ($spendingPatterns as $pattern) {
            $patternPredictions = $pattern->generatePredictions();
            $predictions = array_merge($predictions, $patternPredictions);
        }

        // Get budget predictions
        $budgetPredictions = $this->getBudgetPredictions($user);
        $predictions = array_merge($predictions, $budgetPredictions);

        // Get goal predictions
        $goalPredictions = $this->getGoalPredictions($user);
        $predictions = array_merge($predictions, $goalPredictions);

        // Sort by date
        usort($predictions, function ($a, $b) {
            $dateA = $a['date'] ?? $a['week_start'] ?? $a['month_start'] ?? '';
            $dateB = $b['date'] ?? $b['week_start'] ?? $b['month_start'] ?? '';
            return strtotime($dateA) - strtotime($dateB);
        });

        return response()->json([
            'success' => true,
            'data' => [
                'predictions' => $predictions,
                'total_predictions' => count($predictions),
                'prediction_types' => [
                    'spending' => count(array_filter($predictions, fn($p) => str_contains($p['type'], 'prediction'))),
                    'budget' => count($budgetPredictions),
                    'goals' => count($goalPredictions)
                ]
            ]
        ]);
    }

    /**
     * Get financial recommendations.
     */
    public function recommendations(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $recommendations = [];

        // Analyze spending patterns
        $spendingRecommendations = $this->getSpendingRecommendations($user);
        $recommendations = array_merge($recommendations, $spendingRecommendations);

        // Analyze savings opportunities
        $savingsRecommendations = $this->getSavingsRecommendations($user);
        $recommendations = array_merge($recommendations, $savingsRecommendations);

        // Analyze budget optimization
        $budgetRecommendations = $this->getBudgetRecommendations($user);
        $recommendations = array_merge($recommendations, $budgetRecommendations);

        // Analyze goal optimization
        $goalRecommendations = $this->getGoalRecommendations($user);
        $recommendations = array_merge($recommendations, $goalRecommendations);

        // Sort by priority
        usort($recommendations, function ($a, $b) {
            $priorityOrder = ['critical' => 4, 'high' => 3, 'medium' => 2, 'low' => 1];
            return $priorityOrder[$b['priority']] - $priorityOrder[$a['priority']];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'recommendations' => $recommendations,
                'total_recommendations' => count($recommendations),
                'priority_breakdown' => [
                    'critical' => count(array_filter($recommendations, fn($r) => $r['priority'] === 'critical')),
                    'high' => count(array_filter($recommendations, fn($r) => $r['priority'] === 'high')),
                    'medium' => count(array_filter($recommendations, fn($r) => $r['priority'] === 'medium')),
                    'low' => count(array_filter($recommendations, fn($r) => $r['priority'] === 'low'))
                ]
            ]
        ]);
    }

    /**
     * Get anomaly detection results.
     */
    public function anomalies(Request $request): JsonResponse
    {
        $user = $request->user();
        $wallet = $user->wallet;
        
        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'User has no wallet'
            ], 404);
        }

        $anomalies = [];

        // Detect spending anomalies
        $spendingAnomalies = $this->detectSpendingAnomalies($wallet);
        $anomalies = array_merge($anomalies, $spendingAnomalies);

        // Detect budget anomalies
        $budgetAnomalies = $this->detectBudgetAnomalies($user);
        $anomalies = array_merge($anomalies, $budgetAnomalies);

        // Detect goal anomalies
        $goalAnomalies = $this->detectGoalAnomalies($user);
        $anomalies = array_merge($anomalies, $goalAnomalies);

        // Sort by severity
        usort($anomalies, function ($a, $b) {
            $severityOrder = ['critical' => 4, 'high' => 3, 'medium' => 2, 'low' => 1];
            return $severityOrder[$b['severity']] - $severityOrder[$a['severity']];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'anomalies' => $anomalies,
                'total_anomalies' => count($anomalies),
                'severity_breakdown' => [
                    'critical' => count(array_filter($anomalies, fn($a) => $a['severity'] === 'critical')),
                    'high' => count(array_filter($anomalies, fn($a) => $a['severity'] === 'high')),
                    'medium' => count(array_filter($anomalies, fn($a) => $a['severity'] === 'medium')),
                    'low' => count(array_filter($anomalies, fn($a) => $a['severity'] === 'low'))
                ]
            ]
        ]);
    }

    /**
     * Get start date based on period.
     */
    private function getStartDate(string $period): Carbon
    {
        return match($period) {
            'daily' => now()->subDays(7),
            'weekly' => now()->subWeeks(4),
            'monthly' => now()->subMonths(6),
            'yearly' => now()->subYears(2),
            default => now()->subMonths(6)
        };
    }

    /**
     * Get financial metrics.
     */
    private function getFinancialMetrics($wallet, $startDate, $endDate): array
    {
        $transactions = $wallet->transactions()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $income = $transactions->where('amount', '>', 0)->sum('amount');
        $expenses = abs($transactions->where('amount', '<', 0)->sum('amount'));
        $netAmount = $income - $expenses;
        $savingsRate = $income > 0 ? ($netAmount / $income) * 100 : 0;

        return [
            'total_income' => $income,
            'total_expenses' => $expenses,
            'net_amount' => $netAmount,
            'savings_rate' => round($savingsRate, 2),
            'transaction_count' => $transactions->count(),
            'average_transaction' => $transactions->count() > 0 ? $transactions->avg('amount') : 0
        ];
    }

    /**
     * Get spending patterns.
     */
    private function getSpendingPatterns($user): array
    {
        $patterns = $user->spendingPatterns()
            ->active()
            ->reliable()
            ->with('category')
            ->get();

        return [
            'total_patterns' => $patterns->count(),
            'patterns_by_type' => $patterns->groupBy('pattern_type'),
            'high_confidence_patterns' => $patterns->where('confidence_score', '>=', 80)->count(),
            'recent_patterns' => $patterns->where('last_updated', '>=', now()->subDays(7))->count()
        ];
    }

    /**
     * Get financial insights.
     */
    private function getFinancialInsights($user): array
    {
        $insights = $user->financialInsights()
            ->active()
            ->orderBy('priority_score', 'desc')
            ->limit(10)
            ->get();

        return [
            'total_insights' => $insights->count(),
            'unread_insights' => $insights->where('is_read', false)->count(),
            'critical_insights' => $insights->where('severity', 'critical')->count(),
            'recent_insights' => $insights->where('insight_date', '>=', now()->subDays(7))->count(),
            'insights' => $insights
        ];
    }

    /**
     * Get predictions.
     */
    private function getPredictions($user): array
    {
        $predictions = [];
        
        // Get spending predictions from patterns
        $patterns = $user->spendingPatterns()->active()->reliable()->get();
        foreach ($patterns as $pattern) {
            $patternPredictions = $pattern->predictions ?? [];
            $predictions = array_merge($predictions, $patternPredictions);
        }

        return [
            'total_predictions' => count($predictions),
            'predictions_by_type' => collect($predictions)->groupBy('type'),
            'upcoming_predictions' => collect($predictions)->filter(function ($prediction) {
                $date = $prediction['date'] ?? $prediction['week_start'] ?? $prediction['month_start'] ?? '';
                return strtotime($date) <= strtotime('+7 days');
            })->count()
        ];
    }

    /**
     * Get category analysis.
     */
    private function getCategoryAnalysis($wallet, $startDate, $endDate): array
    {
        $transactions = $wallet->transactions()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('amount', '<', 0)
            ->with('category')
            ->get();

        $byCategory = $transactions->groupBy('category.name')
            ->map(function ($transactions) {
                return [
                    'count' => $transactions->count(),
                    'total_amount' => abs($transactions->sum('amount')),
                    'average_amount' => abs($transactions->avg('amount')),
                    'percentage' => 0 // Will be calculated below
                ];
            });

        $totalExpenses = $byCategory->sum('total_amount');
        
        // Calculate percentages
        $byCategory->transform(function ($data) use ($totalExpenses) {
            $data['percentage'] = $totalExpenses > 0 ? round(($data['total_amount'] / $totalExpenses) * 100, 2) : 0;
            return $data;
        });

        return [
            'total_categories' => $byCategory->count(),
            'top_categories' => $byCategory->sortByDesc('total_amount')->take(5),
            'category_breakdown' => $byCategory
        ];
    }

    /**
     * Get trend analysis.
     */
    private function getTrendAnalysis($wallet, $startDate, $endDate): array
    {
        $transactions = $wallet->transactions()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $dailyTrends = $transactions->groupBy(function ($transaction) {
            return $transaction->created_at->format('Y-m-d');
        })->map(function ($transactions) {
            return [
                'income' => $transactions->where('amount', '>', 0)->sum('amount'),
                'expenses' => abs($transactions->where('amount', '<', 0)->sum('amount')),
                'net' => $transactions->sum('amount')
            ];
        });

        $weeklyTrends = $transactions->groupBy(function ($transaction) {
            return $transaction->created_at->format('Y-W');
        })->map(function ($transactions) {
            return [
                'income' => $transactions->where('amount', '>', 0)->sum('amount'),
                'expenses' => abs($transactions->where('amount', '<', 0)->sum('amount')),
                'net' => $transactions->sum('amount')
            ];
        });

        return [
            'daily_trends' => $dailyTrends,
            'weekly_trends' => $weeklyTrends,
            'trend_summary' => [
                'total_days' => $dailyTrends->count(),
                'total_weeks' => $weeklyTrends->count(),
                'average_daily_net' => $dailyTrends->avg('net'),
                'average_weekly_net' => $weeklyTrends->avg('net')
            ]
        ];
    }

    /**
     * Generate insights for user.
     */
    private function generateInsights($user): void
    {
        // Generate spending analysis insight
        FinancialInsight::generateSpendingAnalysis($user);
        
        // Generate savings opportunity insight
        FinancialInsight::generateSavingsOpportunity($user);
        
        // Generate risk alert insight
        FinancialInsight::generateRiskAlert($user);
    }

    /**
     * Analyze patterns for user.
     */
    private function analyzePatterns($user): void
    {
        // Analyze daily patterns
        $dailyPatterns = SpendingPattern::analyzeDailyPatterns($user);
        if (!empty($dailyPatterns)) {
            SpendingPattern::updateOrCreate(
                ['user_id' => $user->id, 'pattern_type' => 'daily'],
                array_merge($dailyPatterns, ['last_updated' => now()])
            );
        }

        // Analyze weekly patterns
        $weeklyPatterns = SpendingPattern::analyzeWeeklyPatterns($user);
        if (!empty($weeklyPatterns)) {
            SpendingPattern::updateOrCreate(
                ['user_id' => $user->id, 'pattern_type' => 'weekly'],
                array_merge($weeklyPatterns, ['last_updated' => now()])
            );
        }

        // Analyze monthly patterns
        $monthlyPatterns = SpendingPattern::analyzeMonthlyPatterns($user);
        if (!empty($monthlyPatterns)) {
            SpendingPattern::updateOrCreate(
                ['user_id' => $user->id, 'pattern_type' => 'monthly'],
                array_merge($monthlyPatterns, ['last_updated' => now()])
            );
        }
    }

    /**
     * Get budget predictions.
     */
    private function getBudgetPredictions($user): array
    {
        $predictions = [];
        $budgets = $user->budgets()->active()->get();

        foreach ($budgets as $budget) {
            $daysRemaining = $budget->days_remaining;
            $dailyBudget = $budget->daily_budget;
            $remainingAmount = $budget->remaining;

            if ($daysRemaining > 0 && $dailyBudget > 0) {
                $predictions[] = [
                    'type' => 'budget_prediction',
                    'budget_id' => $budget->id,
                    'budget_name' => $budget->name,
                    'predicted_remaining' => $remainingAmount - ($dailyBudget * $daysRemaining),
                    'daily_budget' => $dailyBudget,
                    'days_remaining' => $daysRemaining,
                    'confidence' => 85
                ];
            }
        }

        return $predictions;
    }

    /**
     * Get goal predictions.
     */
    private function getGoalPredictions($user): array
    {
        $predictions = [];
        $goals = $user->goals()->active()->get();

        foreach ($goals as $goal) {
            if ($goal->target_date) {
                $monthsRemaining = $goal->target_date->diffInMonths(now());
                $monthlyNeeded = $goal->monthly_savings_needed;

                $predictions[] = [
                    'type' => 'goal_prediction',
                    'goal_id' => $goal->id,
                    'goal_name' => $goal->name,
                    'predicted_completion_date' => $goal->target_date->format('Y-m-d'),
                    'monthly_savings_needed' => $monthlyNeeded,
                    'months_remaining' => $monthsRemaining,
                    'confidence' => 80
                ];
            }
        }

        return $predictions;
    }

    /**
     * Get spending recommendations.
     */
    private function getSpendingRecommendations($user): array
    {
        $recommendations = [];
        $wallet = $user->wallet;
        
        if (!$wallet) return $recommendations;

        $monthlyExpenses = abs($wallet->transactions()
            ->where('amount', '<', 0)
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('amount'));

        $previousMonthExpenses = abs($wallet->transactions()
            ->where('amount', '<', 0)
            ->where('created_at', '>=', now()->subMonth()->startOfMonth())
            ->where('created_at', '<', now()->startOfMonth())
            ->sum('amount'));

        if ($monthlyExpenses > $previousMonthExpenses * 1.2) {
            $recommendations[] = [
                'type' => 'spending_reduction',
                'priority' => 'high',
                'title' => 'Reduce Monthly Spending',
                'description' => 'Your spending has increased by ' . round((($monthlyExpenses - $previousMonthExpenses) / $previousMonthExpenses) * 100, 1) . '% this month',
                'action' => 'Review your expenses and identify areas to cut back'
            ];
        }

        return $recommendations;
    }

    /**
     * Get savings recommendations.
     */
    private function getSavingsRecommendations($user): array
    {
        $recommendations = [];
        $wallet = $user->wallet;
        
        if (!$wallet) return $recommendations;

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
            $recommendations[] = [
                'type' => 'savings_increase',
                'priority' => 'medium',
                'title' => 'Increase Savings Rate',
                'description' => 'Your current savings rate is ' . round($savingsRate, 1) . '%. Aim for at least 20%.',
                'action' => 'Set up automatic savings transfers and review expenses'
            ];
        }

        return $recommendations;
    }

    /**
     * Get budget recommendations.
     */
    private function getBudgetRecommendations($user): array
    {
        $recommendations = [];
        $budgets = $user->budgets()->active()->get();

        foreach ($budgets as $budget) {
            if ($budget->spent_percentage >= 90) {
                $recommendations[] = [
                    'type' => 'budget_warning',
                    'priority' => 'high',
                    'title' => 'Budget Limit Approaching',
                    'description' => "Budget '{$budget->name}' is {$budget->spent_percentage}% spent",
                    'action' => 'Review spending in this category or extend budget'
                ];
            }
        }

        return $recommendations;
    }

    /**
     * Get goal recommendations.
     */
    private function getGoalRecommendations($user): array
    {
        $recommendations = [];
        $goals = $user->goals()->active()->get();

        foreach ($goals as $goal) {
            if ($goal->progress_percentage < 25 && $goal->target_date) {
                $monthsRemaining = $goal->target_date->diffInMonths(now());
                if ($monthsRemaining <= 3) {
                    $recommendations[] = [
                        'type' => 'goal_urgency',
                        'priority' => 'high',
                        'title' => 'Goal Deadline Approaching',
                        'description' => "Goal '{$goal->name}' has low progress and deadline is approaching",
                        'action' => 'Increase monthly contributions or extend deadline'
                    ];
                }
            }
        }

        return $recommendations;
    }

    /**
     * Detect spending anomalies.
     */
    private function detectSpendingAnomalies($wallet): array
    {
        $anomalies = [];
        
        // Check for unusual daily spending
        $recentTransactions = $wallet->transactions()
            ->where('amount', '<', 0)
            ->where('created_at', '>=', now()->subDays(7))
            ->get();

        $dailySpending = $recentTransactions->groupBy(function ($transaction) {
            return $transaction->created_at->format('Y-m-d');
        })->map(function ($transactions) {
            return abs($transactions->sum('amount'));
        });

        $averageDaily = $dailySpending->avg();
        $maxDaily = $dailySpending->max();

        if ($maxDaily > $averageDaily * 3) {
            $anomalies[] = [
                'type' => 'unusual_daily_spending',
                'severity' => 'high',
                'description' => 'Unusual daily spending detected',
                'data' => [
                    'average_daily' => $averageDaily,
                    'max_daily' => $maxDaily,
                    'multiplier' => round($maxDaily / $averageDaily, 2)
                ]
            ];
        }

        return $anomalies;
    }

    /**
     * Detect budget anomalies.
     */
    private function detectBudgetAnomalies($user): array
    {
        $anomalies = [];
        $budgets = $user->budgets()->active()->get();

        foreach ($budgets as $budget) {
            if ($budget->spent_percentage > 100) {
                $anomalies[] = [
                    'type' => 'budget_exceeded',
                    'severity' => 'critical',
                    'description' => "Budget '{$budget->name}' has been exceeded",
                    'data' => [
                        'budget_amount' => $budget->amount,
                        'spent_amount' => $budget->spent,
                        'excess_amount' => $budget->spent - $budget->amount
                    ]
                ];
            }
        }

        return $anomalies;
    }

    /**
     * Detect goal anomalies.
     */
    private function detectGoalAnomalies($user): array
    {
        $anomalies = [];
        $goals = $user->goals()->active()->get();

        foreach ($goals as $goal) {
            if ($goal->target_date && $goal->target_date->isPast()) {
                $anomalies[] = [
                    'type' => 'goal_overdue',
                    'severity' => 'high',
                    'description' => "Goal '{$goal->name}' is overdue",
                    'data' => [
                        'target_date' => $goal->target_date->format('Y-m-d'),
                        'days_overdue' => $goal->target_date->diffInDays(now()),
                        'progress' => $goal->progress_percentage
                    ]
                ];
            }
        }

        return $anomalies;
    }
}
