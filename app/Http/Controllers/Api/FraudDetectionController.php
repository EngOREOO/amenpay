<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FraudDetection;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FraudDetectionController extends Controller
{
    /**
     * Get fraud detection statistics
     */
    public function getStatistics(): JsonResponse
    {
        try {
            $stats = FraudDetection::getStatistics();
            $trends = FraudDetection::getFraudTrends();

            return response()->json([
                'success' => true,
                'data' => [
                    'statistics' => $stats,
                    'trends' => $trends
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve fraud statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all fraud detections with filtering and pagination
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = FraudDetection::with(['user', 'transaction', 'investigator']);

            // Apply filters
            if ($request->filled('fraud_type')) {
                $query->byFraudType($request->fraud_type);
            }

            if ($request->filled('risk_level')) {
                $query->byRiskLevel($request->risk_level);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->byDateRange($request->date_from, $request->date_to);
            }

            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            // Apply sorting
            $sortBy = $request->get('sort_by', 'detected_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $fraudDetections = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $fraudDetections
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve fraud detections',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific fraud detection
     */
    public function show(int $id): JsonResponse
    {
        try {
            $fraudDetection = FraudDetection::with([
                'user', 
                'transaction', 
                'investigator',
                'regulatoryReports'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $fraudDetection
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fraud detection not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create a new fraud detection
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'transaction_id' => 'nullable|exists:transactions,id',
                'fraud_type' => 'required|in:transaction_fraud,account_takeover,identity_theft,money_laundering',
                'ai_analysis' => 'nullable|array',
                'behavioral_patterns' => 'nullable|array',
                'location_analysis' => 'nullable|array',
                'device_analysis' => 'nullable|array',
                'network_analysis' => 'nullable|array',
                'temporal_patterns' => 'nullable|array',
                'amount_patterns' => 'nullable|array',
                'velocity_patterns' => 'nullable|array',
                'relationship_analysis' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $fraudDetection = FraudDetection::create([
                'user_id' => $request->user_id,
                'transaction_id' => $request->transaction_id,
                'fraud_type' => $request->fraud_type,
                'ai_analysis' => $request->ai_analysis ?? [],
                'behavioral_patterns' => $request->behavioral_patterns ?? [],
                'location_analysis' => $request->location_analysis ?? [],
                'device_analysis' => $request->device_analysis ?? [],
                'network_analysis' => $request->network_analysis ?? [],
                'temporal_patterns' => $request->temporal_patterns ?? [],
                'amount_patterns' => $request->amount_patterns ?? [],
                'velocity_patterns' => $request->velocity_patterns ?? [],
                'relationship_analysis' => $request->relationship_analysis ?? [],
                'status' => 'pending',
                'detected_at' => now()
            ]);

            // Analyze fraud patterns and calculate risk score
            $fraudDetection->analyzeFraudPatterns();
            $fraudDetection->calculateRiskScore();
            $fraudDetection->generateComplianceFlags();

            // If high risk, automatically mark for investigation
            if ($fraudDetection->is_high_risk) {
                $fraudDetection->markAsInvestigating(Auth::id());
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Fraud detection created successfully',
                'data' => $fraudDetection->load(['user', 'transaction'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create fraud detection',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update fraud detection status
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,investigating,confirmed,false_positive,resolved',
                'investigation_notes' => 'nullable|string',
                'mitigation_actions' => 'nullable|array',
                'compliance_flags' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $fraudDetection = FraudDetection::findOrFail($id);

            $updateData = [
                'status' => $request->status
            ];

            if ($request->filled('investigation_notes')) {
                $updateData['investigation_notes'] = $request->investigation_notes;
            }

            if ($request->filled('mitigation_actions')) {
                $updateData['mitigation_actions'] = $request->mitigation_actions;
            }

            if ($request->filled('compliance_flags')) {
                $updateData['compliance_flags'] = $request->mitigation_actions;
            }

            // Update timestamps based on status
            if ($request->status === 'investigating' && $fraudDetection->status !== 'investigating') {
                $updateData['investigated_at'] = now();
                $updateData['investigated_by'] = Auth::id();
            }

            if ($request->status === 'resolved' && $fraudDetection->status !== 'resolved') {
                $updateData['resolved_at'] = now();
            }

            $fraudDetection->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Fraud detection updated successfully',
                'data' => $fraudDetection->fresh(['user', 'transaction', 'investigator'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update fraud detection',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark fraud detection as investigating
     */
    public function markAsInvestigating(int $id): JsonResponse
    {
        try {
            $fraudDetection = FraudDetection::findOrFail($id);
            $fraudDetection->markAsInvestigating(Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Fraud detection marked as investigating',
                'data' => $fraudDetection->fresh(['user', 'transaction', 'investigator'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark fraud detection as investigating',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark fraud detection as resolved
     */
    public function markAsResolved(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'resolution' => 'required|string',
                'actions' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $fraudDetection = FraudDetection::findOrFail($id);
            $fraudDetection->markAsResolved($request->resolution, $request->actions ?? []);

            return response()->json([
                'success' => true,
                'message' => 'Fraud detection marked as resolved',
                'data' => $fraudDetection->fresh(['user', 'transaction', 'investigator'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark fraud detection as resolved',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark fraud detection as false positive
     */
    public function markAsFalsePositive(Request $request, int $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'reason' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $fraudDetection = FraudDetection::findOrFail($id);
            $fraudDetection->markAsFalsePositive($request->reason);

            return response()->json([
                'success' => true,
                'message' => 'Fraud detection marked as false positive',
                'data' => $fraudDetection->fresh(['user', 'transaction', 'investigator'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark fraud detection as false positive',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get fraud detections by user
     */
    public function getByUser(int $userId): JsonResponse
    {
        try {
            $fraudDetections = FraudDetection::with(['transaction', 'investigator'])
                ->where('user_id', $userId)
                ->orderBy('detected_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $fraudDetections
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user fraud detections',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get critical fraud detections
     */
    public function getCritical(): JsonResponse
    {
        try {
            $criticalDetections = FraudDetection::with(['user', 'transaction', 'investigator'])
                ->critical()
                ->orderBy('detected_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $criticalDetections
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve critical fraud detections',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get high-risk fraud detections
     */
    public function getHighRisk(): JsonResponse
    {
        try {
            $highRiskDetections = FraudDetection::with(['user', 'transaction', 'investigator'])
                ->highRisk()
                ->orderBy('detected_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $highRiskDetections
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve high-risk fraud detections',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get fraud detections under investigation
     */
    public function getUnderInvestigation(): JsonResponse
    {
        try {
            $underInvestigation = FraudDetection::with(['user', 'transaction', 'investigator'])
                ->underInvestigation()
                ->orderBy('detected_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $underInvestigation
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve fraud detections under investigation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get fraud detection trends
     */
    public function getTrends(): JsonResponse
    {
        try {
            $trends = FraudDetection::getFraudTrends();

            return response()->json([
                'success' => true,
                'data' => $trends
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve fraud detection trends',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Analyze transaction for fraud
     */
    public function analyzeTransaction(int $transactionId): JsonResponse
    {
        try {
            $transaction = Transaction::with(['wallet.user'])->findOrFail($transactionId);
            
            // Check if fraud detection already exists
            $existingDetection = FraudDetection::where('transaction_id', $transactionId)->first();
            if ($existingDetection) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction already analyzed for fraud',
                    'data' => $existingDetection
                ], 400);
            }

            // Perform AI-powered fraud analysis
            $fraudAnalysis = $this->performTransactionFraudAnalysis($transaction);

            if ($fraudAnalysis['risk_score'] >= 40) {
                // Create fraud detection record
                $fraudDetection = FraudDetection::create([
                    'user_id' => $transaction->wallet->user_id,
                    'transaction_id' => $transactionId,
                    'fraud_type' => $fraudAnalysis['fraud_type'],
                    'ai_analysis' => $fraudAnalysis['ai_analysis'],
                    'behavioral_patterns' => $fraudAnalysis['behavioral_patterns'],
                    'location_analysis' => $fraudAnalysis['location_analysis'],
                    'device_analysis' => $fraudAnalysis['device_analysis'],
                    'network_analysis' => $fraudAnalysis['network_analysis'],
                    'temporal_patterns' => $fraudAnalysis['temporal_patterns'],
                    'amount_patterns' => $fraudAnalysis['amount_patterns'],
                    'velocity_patterns' => $fraudAnalysis['velocity_patterns'],
                    'relationship_analysis' => $fraudAnalysis['relationship_analysis'],
                    'status' => 'pending',
                    'detected_at' => now()
                ]);

                // Analyze patterns and calculate risk
                $fraudDetection->analyzeFraudPatterns();
                $fraudDetection->calculateRiskScore();
                $fraudDetection->generateComplianceFlags();

                return response()->json([
                    'success' => true,
                    'message' => 'Transaction analyzed for fraud',
                    'data' => [
                        'fraud_detection' => $fraudDetection,
                        'analysis' => $fraudAnalysis
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaction analyzed - no fraud detected',
                'data' => [
                    'analysis' => $fraudAnalysis
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to analyze transaction for fraud',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Perform AI-powered transaction fraud analysis
     */
    private function performTransactionFraudAnalysis(Transaction $transaction): array
    {
        $user = $transaction->wallet->user;
        $userTransactions = $user->transactions()->where('id', '!=', $transaction->id)->get();

        // Calculate risk factors
        $locationRisk = $this->calculateLocationRisk($transaction, $userTransactions);
        $amountRisk = $this->calculateAmountRisk($transaction, $userTransactions);
        $velocityRisk = $this->calculateVelocityRisk($transaction, $userTransactions);
        $temporalRisk = $this->calculateTemporalRisk($transaction, $userTransactions);
        $deviceRisk = $this->calculateDeviceRisk($transaction, $user);

        // Aggregate risk score
        $riskScore = ($locationRisk + $amountRisk + $velocityRisk + $temporalRisk + $deviceRisk) / 5;

        // Determine fraud type
        $fraudType = $this->determineFraudType($riskScore, $locationRisk, $amountRisk, $velocityRisk);

        return [
            'risk_score' => $riskScore,
            'fraud_type' => $fraudType,
            'ai_analysis' => [
                'confidence' => $this->calculateConfidence($riskScore),
                'model_version' => '1.0.0',
                'analysis_timestamp' => now()->toISOString()
            ],
            'behavioral_patterns' => [
                'location_anomaly' => $locationRisk > 70,
                'amount_anomaly' => $amountRisk > 70,
                'velocity_anomaly' => $velocityRisk > 70,
                'temporal_anomaly' => $temporalRisk > 70,
                'device_anomaly' => $deviceRisk > 70
            ],
            'location_analysis' => [
                'current_location' => $transaction->metadata['location'] ?? 'unknown',
                'usual_locations' => $userTransactions->pluck('metadata.location')->unique()->toArray(),
                'location_risk' => $locationRisk
            ],
            'device_analysis' => [
                'device_token' => $transaction->metadata['device_token'] ?? 'unknown',
                'device_risk' => $deviceRisk
            ],
            'network_analysis' => [
                'ip_address' => $transaction->metadata['ip_address'] ?? 'unknown',
                'network_risk' => $this->calculateNetworkRisk($transaction)
            ],
            'temporal_patterns' => [
                'transaction_hour' => $transaction->created_at->hour,
                'usual_hours' => $this->getUsualTransactionHours($userTransactions),
                'temporal_risk' => $temporalRisk
            ],
            'amount_patterns' => [
                'current_amount' => $transaction->amount,
                'average_amount' => $userTransactions->avg('amount'),
                'max_amount' => $userTransactions->max('amount'),
                'amount_risk' => $amountRisk
            ],
            'velocity_patterns' => [
                'recent_transactions' => $userTransactions->where('created_at', '>=', now()->subHours(1))->count(),
                'velocity_risk' => $velocityRisk
            ],
            'relationship_analysis' => [
                'suspicious_connections' => $this->detectSuspiciousConnections($transaction, $userTransactions),
                'relationship_risk' => $this->calculateRelationshipRisk($transaction, $userTransactions)
            ]
        ];
    }

    /**
     * Calculate location-based risk
     */
    private function calculateLocationRisk(Transaction $transaction, $userTransactions): float
    {
        $currentLocation = $transaction->metadata['location'] ?? 'unknown';
        $usualLocations = $userTransactions->pluck('metadata.location')->unique()->toArray();
        
        if (empty($usualLocations) || in_array($currentLocation, $usualLocations)) {
            return 10; // Low risk
        }
        
        return 80; // High risk for unusual location
    }

    /**
     * Calculate amount-based risk
     */
    private function calculateAmountRisk(Transaction $transaction, $userTransactions): float
    {
        $currentAmount = $transaction->amount;
        $averageAmount = $userTransactions->avg('amount') ?? 0;
        $maxAmount = $userTransactions->max('amount') ?? 0;
        
        if ($averageAmount == 0) return 20;
        
        $amountRatio = $currentAmount / $averageAmount;
        
        if ($amountRatio > 10) return 90; // Very high risk
        if ($amountRatio > 5) return 70;  // High risk
        if ($amountRatio > 3) return 50;  // Medium risk
        if ($amountRatio > 2) return 30;  // Low risk
        
        return 10; // Very low risk
    }

    /**
     * Calculate velocity-based risk
     */
    private function calculateVelocityRisk(Transaction $transaction, $userTransactions): float
    {
        $recentTransactions = $userTransactions->where('created_at', '>=', now()->subHours(1))->count();
        
        if ($recentTransactions > 20) return 90; // Very high risk
        if ($recentTransactions > 15) return 80; // High risk
        if ($recentTransactions > 10) return 60; // Medium risk
        if ($recentTransactions > 5) return 40;  // Low risk
        
        return 10; // Very low risk
    }

    /**
     * Calculate temporal risk
     */
    private function calculateTemporalRisk(Transaction $transaction, $userTransactions): float
    {
        $hour = $transaction->created_at->hour;
        
        // Unusual hours (2-5 AM)
        if ($hour >= 2 && $hour <= 5) return 70;
        
        // Late night (11 PM - 1 AM)
        if ($hour >= 23 || $hour <= 1) return 50;
        
        // Normal hours
        return 10;
    }

    /**
     * Calculate device risk
     */
    private function calculateDeviceRisk(Transaction $transaction, User $user): float
    {
        $deviceToken = $transaction->metadata['device_token'] ?? 'unknown';
        $userDevices = $user->pushNotificationDevices()->pluck('device_token')->toArray();
        
        if (empty($userDevices) || in_array($deviceToken, $userDevices)) {
            return 10; // Low risk
        }
        
        return 80; // High risk for unknown device
    }

    /**
     * Calculate network risk
     */
    private function calculateNetworkRisk(Transaction $transaction): float
    {
        $ipAddress = $transaction->metadata['ip_address'] ?? 'unknown';
        
        // Simple IP risk calculation - in production, integrate with IP reputation service
        if ($ipAddress === 'unknown') return 30;
        
        // Check for suspicious IP patterns
        if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return 20; // Public IP - lower risk
        }
        
        return 40; // Private IP - slightly higher risk
    }

    /**
     * Get usual transaction hours
     */
    private function getUsualTransactionHours($userTransactions): array
    {
        return $userTransactions->pluck('created_at.hour')->countBy()->sortDesc()->take(5)->keys()->toArray();
    }

    /**
     * Detect suspicious connections
     */
    private function detectSuspiciousConnections(Transaction $transaction, $userTransactions): array
    {
        // In production, implement more sophisticated connection analysis
        return [];
    }

    /**
     * Calculate relationship risk
     */
    private function calculateRelationshipRisk(Transaction $transaction, $userTransactions): float
    {
        // In production, implement more sophisticated relationship analysis
        return 10;
    }

    /**
     * Determine fraud type based on risk factors
     */
    private function determineFraudType(float $riskScore, float $locationRisk, float $amountRisk, float $velocityRisk): string
    {
        if ($amountRisk > 80 && $velocityRisk > 80) return 'money_laundering';
        if ($locationRisk > 80) return 'account_takeover';
        if ($riskScore > 70) return 'transaction_fraud';
        
        return 'transaction_fraud';
    }

    /**
     * Calculate confidence score
     */
    private function calculateConfidence(float $riskScore): float
    {
        // Higher risk scores have higher confidence
        return min(95, 50 + ($riskScore * 0.45));
    }
}
