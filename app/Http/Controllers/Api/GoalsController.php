<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class GoalsController extends Controller
{
    /**
     * Get user's goals listing.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Goal::where('user_id', $request->user()->id);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Filter by priority
        if ($request->priority) {
            $query->where('priority', $request->priority);
        }

        // Filter by completion status
        if ($request->completed !== null) {
            if ($request->completed) {
                $query->where('status', 'completed');
            } else {
                $query->where('status', '!=', 'completed');
            }
        }

        $goals = $query->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $goals
        ]);
    }

    /**
     * Get goal details.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $goal = Goal::where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $goal
        ]);
    }

    /**
     * Create new goal.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:savings,purchase,investment,emergency,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'target_date' => 'nullable|date|after:today',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'milestones' => 'nullable|array',
            'notifications' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $goal = Goal::create([
            'user_id' => $request->user()->id,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'description_ar' => $request->description_ar,
            'description_en' => $request->description_en,
            'target_amount' => $request->target_amount,
            'current_amount' => 0,
            'progress_percentage' => 0,
            'type' => $request->type,
            'priority' => $request->priority,
            'target_date' => $request->target_date,
            'icon' => $request->icon,
            'color' => $request->color,
            'milestones' => $request->milestones ?? [],
            'notifications' => $request->notifications ?? []
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Goal created successfully',
            'data' => $goal
        ], 201);
    }

    /**
     * Update goal.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name_ar' => 'sometimes|string|max:255',
            'name_en' => 'sometimes|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'target_amount' => 'sometimes|numeric|min:0.01',
            'type' => 'sometimes|in:savings,purchase,investment,emergency,other',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'target_date' => 'nullable|date|after:today',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'milestones' => 'nullable|array',
            'notifications' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $goal = Goal::where('user_id', $request->user()->id)
            ->findOrFail($id);

        // Update goal
        $goal->update($request->except(['current_amount', 'progress_percentage']));

        // Recalculate progress if target amount was changed
        if ($request->has('target_amount')) {
            $goal->updateProgress();
        }

        return response()->json([
            'success' => true,
            'message' => 'Goal updated successfully',
            'data' => $goal->fresh()
        ]);
    }

    /**
     * Delete goal.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $goal = Goal::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $goal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Goal deleted successfully'
        ]);
    }

    /**
     * Add amount to goal progress.
     */
    public function addAmount(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $goal = Goal::where('user_id', $request->user()->id)
            ->findOrFail($id);

        if ($goal->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot add amount to non-active goal'
            ], 400);
        }

        $goal->addAmount($request->amount);

        return response()->json([
            'success' => true,
            'message' => 'Amount added to goal successfully',
            'data' => $goal->fresh()
        ]);
    }

    /**
     * Update goal status.
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,paused,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $goal = Goal::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $oldStatus = $goal->status;
        $goal->update(['status' => $request->status]);

        // If marking as completed, ensure progress is 100%
        if ($request->status === 'completed') {
            $goal->update([
                'progress_percentage' => 100,
                'completed_at' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Goal status updated from {$oldStatus} to {$request->status}",
            'data' => $goal->fresh()
        ]);
    }

    /**
     * Extend goal target date.
     */
    public function extendTargetDate(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'new_target_date' => 'required|date|after:today'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $goal = Goal::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $goal->extendTargetDate(Carbon::parse($request->new_target_date));

        return response()->json([
            'success' => true,
            'message' => 'Goal target date extended successfully',
            'data' => $goal->fresh()
        ]);
    }

    /**
     * Add milestone to goal.
     */
    public function addMilestone(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $goal = Goal::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $goal->addMilestone(
            $request->title,
            $request->amount,
            $request->description
        );

        return response()->json([
            'success' => true,
            'message' => 'Milestone added successfully',
            'data' => $goal->fresh()
        ]);
    }

    /**
     * Mark milestone as achieved.
     */
    public function achieveMilestone(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'milestone_id' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $goal = Goal::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $goal->achieveMilestone($request->milestone_id);

        return response()->json([
            'success' => true,
            'message' => 'Milestone marked as achieved',
            'data' => $goal->fresh()
        ]);
    }

    /**
     * Get goals overview and statistics.
     */
    public function overview(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Get goal statistics
        $stats = [
            'total_goals' => $user->goals()->count(),
            'active_goals' => $user->goals()->active()->count(),
            'completed_goals' => $user->goals()->completed()->count(),
            'paused_goals' => $user->goals()->paused()->count(),
            'overdue_goals' => $user->goals()->overdue()->count(),
            'total_target_amount' => $user->goals()->sum('target_amount'),
            'total_current_amount' => $user->goals()->sum('current_amount'),
            'total_progress' => $user->goals()->avg('progress_percentage')
        ];

        // Get recent goals
        $recentGoals = $user->goals()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get goals by type
        $goalsByType = $user->goals()
            ->active()
            ->get()
            ->groupBy('type');

        // Get goals by priority
        $goalsByPriority = $user->goals()
            ->active()
            ->get()
            ->groupBy('priority');

        // Get savings trends
        $savingsTrends = $this->getSavingsTrends($user);

        return response()->json([
            'success' => true,
            'data' => [
                'statistics' => $stats,
                'recent_goals' => $recentGoals,
                'goals_by_type' => $goalsByType,
                'goals_by_priority' => $goalsByPriority,
                'savings_trends' => $savingsTrends
            ]
        ]);
    }

    /**
     * Get savings trends for goals.
     */
    private function getSavingsTrends($user): array
    {
        $trends = [];
        
        // Last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $startDate = $month->startOfMonth();
            $endDate = $month->endOfMonth();

            $goals = $user->goals()
                ->where('created_at', '<=', $endDate)
                ->get();

            $trends[] = [
                'month' => $month->format('Y-m'),
                'total_goals' => $goals->count(),
                'target_amount' => $goals->sum('target_amount'),
                'current_amount' => $goals->sum('current_amount'),
                'progress' => $goals->avg('progress_percentage')
            ];
        }

        return $trends;
    }

    /**
     * Get goal alerts and reminders.
     */
    public function alerts(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $alerts = [];

        // Overdue goals
        $overdueGoals = $user->goals()->overdue()->get();
        foreach ($overdueGoals as $goal) {
            $alerts[] = [
                'type' => 'overdue',
                'severity' => 'high',
                'goal' => $goal,
                'message' => "Goal '{$goal->name}' is overdue"
            ];
        }

        // Goals approaching target date (within 30 days)
        $approachingDeadline = $user->goals()
            ->active()
            ->whereNotNull('target_date')
            ->where('target_date', '<=', now()->addDays(30))
            ->where('target_date', '>', now())
            ->get();

        foreach ($approachingDeadline as $goal) {
            $daysLeft = $goal->target_date->diffInDays(now());
            $alerts[] = [
                'type' => 'deadline_approaching',
                'severity' => 'medium',
                'goal' => $goal,
                'message' => "Goal '{$goal->name}' deadline in {$daysLeft} days"
            ];
        }

        // Goals with low progress (less than 25% with target date within 3 months)
        $lowProgressGoals = $user->goals()
            ->active()
            ->whereNotNull('target_date')
            ->where('target_date', '<=', now()->addMonths(3))
            ->where('progress_percentage', '<', 25)
            ->get();

        foreach ($lowProgressGoals as $goal) {
            $alerts[] = [
                'type' => 'low_progress',
                'severity' => 'medium',
                'goal' => $goal,
                'message' => "Goal '{$goal->name}' has low progress ({$goal->progress_percentage}%)"
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
     * Get savings recommendations.
     */
    public function recommendations(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $recommendations = [];

        // Analyze spending patterns and suggest savings
        $monthlyIncome = $user->wallet->transactions()
            ->where('amount', '>', 0)
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('amount');

        $monthlyExpenses = abs($user->wallet->transactions()
            ->where('amount', '<', 0)
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('amount'));

        $savingsRate = $monthlyIncome > 0 ? (($monthlyIncome - $monthlyExpenses) / $monthlyIncome) * 100 : 0;

        // Savings rate recommendations
        if ($savingsRate < 20) {
            $recommendations[] = [
                'type' => 'savings_rate',
                'priority' => 'high',
                'title' => 'Increase Savings Rate',
                'description' => 'Your current savings rate is ' . round($savingsRate, 1) . '%. Aim for at least 20% of your income.',
                'action' => 'Review your expenses and identify areas to cut back'
            ];
        }

        // Goal-based recommendations
        $activeGoals = $user->goals()->active()->get();
        foreach ($activeGoals as $goal) {
            if ($goal->progress_percentage < 50 && $goal->target_date) {
                $monthsLeft = $goal->target_date->diffInMonths(now());
                $monthlyNeeded = $goal->monthly_savings_needed;
                
                if ($monthlyNeeded > $monthlyIncome * 0.3) {
                    $recommendations[] = [
                        'type' => 'goal_funding',
                        'priority' => 'medium',
                        'title' => 'Goal Funding Challenge',
                        'description' => "Goal '{$goal->name}' requires SAR {$monthlyNeeded} monthly. Consider extending the timeline or increasing income.",
                        'action' => 'Review goal timeline or increase monthly savings'
                    ];
                }
            }
        }

        // Emergency fund recommendations
        $emergencyGoals = $user->goals()->where('type', 'emergency')->active()->get();
        if ($emergencyGoals->isEmpty()) {
            $recommendations[] = [
                'type' => 'emergency_fund',
                'priority' => 'high',
                'title' => 'Create Emergency Fund',
                'description' => 'Emergency funds should cover 3-6 months of expenses. Start with a small monthly contribution.',
                'action' => 'Create an emergency fund goal with monthly contributions'
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'recommendations' => $recommendations,
                'total_recommendations' => count($recommendations),
                'high_priority' => collect($recommendations)->where('priority', 'high')->count(),
                'medium_priority' => collect($recommendations)->where('priority', 'medium')->count(),
                'savings_analysis' => [
                    'monthly_income' => $monthlyIncome,
                    'monthly_expenses' => $monthlyExpenses,
                    'savings_rate' => round($savingsRate, 1)
                ]
            ]
        ]);
    }
}
