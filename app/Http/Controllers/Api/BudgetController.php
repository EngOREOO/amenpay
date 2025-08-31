<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BudgetController extends Controller
{
    /**
     * Get user's budgets listing.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Budget::where('user_id', $request->user()->id)
            ->with(['category']);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by period
        if ($request->period) {
            $query->where('period', $request->period);
        }

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by date range
        if ($request->start_date) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->where('end_date', '<=', $request->end_date);
        }

        $budgets = $query->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $budgets
        ]);
    }

    /**
     * Get budget details.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $budget = Budget::where('user_id', $request->user()->id)
            ->with(['category', 'transactions'])
            ->findOrFail($id);

        // Update spent amount from transactions
        $budget->updateSpentAmount();

        return response()->json([
            'success' => true,
            'data' => $budget->fresh(['category', 'transactions'])
        ]);
    }

    /**
     * Create new budget.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'nullable|exists:categories,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'period' => 'required|in:daily,weekly,monthly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'notifications' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if budget already exists for this category and period
        if ($request->category_id) {
            $existingBudget = Budget::where('user_id', $request->user()->id)
                ->where('category_id', $request->category_id)
                ->where('period', $request->period)
                ->where('status', 'active')
                ->first();

            if ($existingBudget) {
                return response()->json([
                    'success' => false,
                    'message' => 'Budget already exists for this category and period'
                ], 400);
            }
        }

        $budget = Budget::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'amount' => $request->amount,
            'spent' => 0,
            'remaining' => $request->amount,
            'period' => $request->period,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'notifications' => $request->notifications ?? []
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Budget created successfully',
            'data' => $budget->load('category')
        ], 201);
    }

    /**
     * Update budget.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'sometimes|string|max:255',
            'name_en' => 'sometimes|string|max:255',
            'amount' => 'sometimes|numeric|min:0.01',
            'end_date' => 'sometimes|date|after:start_date',
            'notifications' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $budget = Budget::where('user_id', $request->user()->id)
            ->findOrFail($id);

        // Update budget
        $budget->update($request->only([
            'name_ar', 'name_en', 'amount', 'end_date', 'notifications'
        ]));

        // Recalculate remaining amount if amount was changed
        if ($request->has('amount')) {
            $budget->update([
                'remaining' => $request->amount - $budget->spent
            ]);
        }

        // Update status
        $budget->updateStatus();

        return response()->json([
            'success' => true,
            'message' => 'Budget updated successfully',
            'data' => $budget->fresh(['category'])
        ]);
    }

    /**
     * Delete budget.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $budget = Budget::where('user_id', $request->user()->id)
            ->findOrFail($id);

        // Check if budget has transactions
        if ($budget->transactions()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete budget with associated transactions'
            ], 400);
        }

        $budget->delete();

        return response()->json([
            'success' => true,
            'message' => 'Budget deleted successfully'
        ]);
    }

    /**
     * Get budget overview and statistics.
     */
    public function overview(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Get budget statistics
        $stats = [
            'total_budgets' => $user->budgets()->count(),
            'active_budgets' => $user->budgets()->active()->count(),
            'completed_budgets' => $user->budgets()->completed()->count(),
            'overdue_budgets' => $user->budgets()->overdue()->count(),
            'total_budget_amount' => $user->budgets()->sum('amount'),
            'total_spent' => $user->budgets()->sum('spent'),
            'total_saved' => $user->budgets()->sum('remaining')
        ];

        // Get recent budgets
        $recentBudgets = $user->budgets()
            ->with(['category'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get budgets by category
        $budgetsByCategory = $user->budgets()
            ->with(['category'])
            ->active()
            ->get()
            ->groupBy('category.name');

        // Get spending trends
        $spendingTrends = $this->getSpendingTrends($user);

        return response()->json([
            'success' => true,
            'data' => [
                'statistics' => $stats,
                'recent_budgets' => $recentBudgets,
                'budgets_by_category' => $budgetsByCategory,
                'spending_trends' => $spendingTrends
            ]
        ]);
    }

    /**
     * Get budget vs actual spending analysis.
     */
    public function budgetVsActual(Request $request): JsonResponse
    {
        $user = $request->user();
        $period = $request->get('period', 'monthly');
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $budgets = $user->budgets()
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orWhereBetween('end_date', [$startDate, $endDate])
            ->with(['category'])
            ->get();

        $analysis = [];

        foreach ($budgets as $budget) {
            // Get actual spending for this budget period
            $actualSpending = $budget->transactions()->sum('amount');
            
            $analysis[] = [
                'budget' => $budget,
                'budgeted_amount' => $budget->amount,
                'actual_spending' => abs($actualSpending),
                'variance' => $budget->amount - abs($actualSpending),
                'variance_percentage' => $budget->amount > 0 ? 
                    round((($budget->amount - abs($actualSpending)) / $budget->amount) * 100, 2) : 0,
                'status' => abs($actualSpending) <= $budget->amount ? 'under_budget' : 'over_budget'
            ];
        }

        // Summary statistics
        $summary = [
            'total_budgeted' => $budgets->sum('amount'),
            'total_actual' => $analysis ? array_sum(array_column($analysis, 'actual_spending')) : 0,
            'total_variance' => $analysis ? array_sum(array_column($analysis, 'variance')) : 0,
            'budgets_under' => collect($analysis)->where('status', 'under_budget')->count(),
            'budgets_over' => collect($analysis)->where('status', 'over_budget')->count()
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'analysis' => $analysis,
                'summary' => $summary,
                'period' => $period,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ]);
    }

    /**
     * Get spending trends for budgets.
     */
    private function getSpendingTrends($user): array
    {
        $trends = [];
        
        // Last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $startDate = $month->startOfMonth();
            $endDate = $month->endOfMonth();

            $budgets = $user->budgets()
                ->whereBetween('start_date', [$startDate, $endDate])
                ->orWhereBetween('end_date', [$startDate, $endDate])
                ->get();

            $trends[] = [
                'month' => $month->format('Y-m'),
                'budgeted' => $budgets->sum('amount'),
                'spent' => $budgets->sum('spent'),
                'saved' => $budgets->sum('remaining')
            ];
        }

        return $trends;
    }

    /**
     * Get budget alerts and warnings.
     */
    public function alerts(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $alerts = [];

        // Overdue budgets
        $overdueBudgets = $user->budgets()->overdue()->get();
        foreach ($overdueBudgets as $budget) {
            $alerts[] = [
                'type' => 'overdue',
                'severity' => 'high',
                'budget' => $budget,
                'message' => "Budget '{$budget->name}' is overdue"
            ];
        }

        // Budgets approaching limit (90%+ spent)
        $criticalBudgets = $user->budgets()
            ->active()
            ->get()
            ->filter(function ($budget) {
                return $budget->spent_percentage >= 90;
            });

        foreach ($criticalBudgets as $budget) {
            $alerts[] = [
                'type' => 'critical',
                'severity' => 'high',
                'budget' => $budget,
                'message' => "Budget '{$budget->name}' is {$budget->spent_percentage}% spent"
            ];
        }

        // Budgets with warning level (75%+ spent)
        $warningBudgets = $user->budgets()
            ->active()
            ->get()
            ->filter(function ($budget) {
                return $budget->spent_percentage >= 75 && $budget->spent_percentage < 90;
            });

        foreach ($warningBudgets as $budget) {
            $alerts[] = [
                'type' => 'warning',
                'severity' => 'medium',
                'budget' => $budget,
                'message' => "Budget '{$budget->name}' is {$budget->spent_percentage}% spent"
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'alerts' => $alerts,
                'total_alerts' => count($alerts),
                'high_severity' => collect($alerts)->where('severity', 'high')->count(),
                'medium_severity' => collect($alerts)->where('severity', 'medium')->count()
            ]
        ]);
    }

    /**
     * Extend budget end date.
     */
    public function extend(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'new_end_date' => 'required|date|after:today'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $budget = Budget::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $budget->extendEndDate(Carbon::parse($request->new_end_date));

        return response()->json([
            'success' => true,
            'message' => 'Budget end date extended successfully',
            'data' => $budget->fresh(['category'])
        ]);
    }

    /**
     * Reset budget for new period.
     */
    public function reset(Request $request, $id): JsonResponse
    {
        $budget = Budget::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $budget->resetForNewPeriod();

        return response()->json([
            'success' => true,
            'message' => 'Budget reset for new period successfully'
        ]);
    }
}
