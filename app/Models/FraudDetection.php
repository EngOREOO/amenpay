<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FraudDetection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'fraud_type',
        'risk_level',
        'risk_score',
        'ai_analysis',
        'fraud_indicators',
        'behavioral_patterns',
        'location_analysis',
        'device_analysis',
        'network_analysis',
        'temporal_patterns',
        'amount_patterns',
        'velocity_patterns',
        'relationship_analysis',
        'status',
        'investigation_notes',
        'mitigation_actions',
        'detected_at',
        'investigated_at',
        'resolved_at',
        'investigated_by',
        'compliance_flags',
        'regulatory_reports'
    ];

    protected $casts = [
        'risk_score' => 'decimal:2',
        'ai_analysis' => 'array',
        'fraud_indicators' => 'array',
        'behavioral_patterns' => 'array',
        'location_analysis' => 'array',
        'device_analysis' => 'array',
        'network_analysis' => 'array',
        'temporal_patterns' => 'array',
        'amount_patterns' => 'array',
        'velocity_patterns' => 'array',
        'relationship_analysis' => 'array',
        'mitigation_actions' => 'array',
        'compliance_flags' => 'array',
        'regulatory_reports' => 'array',
        'detected_at' => 'datetime',
        'investigated_at' => 'datetime',
        'resolved_at' => 'datetime'
    ];

    protected $appends = [
        'is_critical',
        'is_high_risk',
        'is_under_investigation',
        'is_resolved',
        'days_since_detected',
        'investigation_duration'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function investigator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'investigated_by');
    }

    public function regulatoryReports(): HasMany
    {
        return $this->hasMany(RegulatoryReport::class);
    }

    // Accessors
    public function getIsCriticalAttribute(): bool
    {
        return $this->risk_level === 'critical';
    }

    public function getIsHighRiskAttribute(): bool
    {
        return in_array($this->risk_level, ['high', 'critical']);
    }

    public function getIsUnderInvestigationAttribute(): bool
    {
        return $this->status === 'investigating';
    }

    public function getIsResolvedAttribute(): bool
    {
        return in_array($this->status, ['resolved', 'false_positive']);
    }

    public function getDaysSinceDetectedAttribute(): int
    {
        return $this->detected_at ? now()->diffInDays($this->detected_at) : 0;
    }

    public function getInvestigationDurationAttribute(): ?int
    {
        if (!$this->investigated_at || !$this->resolved_at) {
            return null;
        }
        return $this->investigated_at->diffInDays($this->resolved_at);
    }

    // Business Logic Methods
    public function analyzeFraudPatterns(): array
    {
        $patterns = [
            'location_anomaly' => $this->detectLocationAnomaly(),
            'device_anomaly' => $this->detectDeviceAnomaly(),
            'temporal_anomaly' => $this->detectTemporalAnomaly(),
            'amount_anomaly' => $this->detectAmountAnomaly(),
            'velocity_anomaly' => $this->detectVelocityAnomaly(),
            'relationship_anomaly' => $this->detectRelationshipAnomaly()
        ];

        $this->update(['fraud_indicators' => $patterns]);
        return $patterns;
    }

    public function calculateRiskScore(): float
    {
        $baseScore = 0;
        $indicators = $this->fraud_indicators ?? [];

        // Location risk
        if (($indicators['location_anomaly'] ?? false)) {
            $baseScore += 25;
        }

        // Device risk
        if (($indicators['device_anomaly'] ?? false)) {
            $baseScore += 20;
        }

        // Temporal risk
        if (($indicators['temporal_anomaly'] ?? false)) {
            $baseScore += 15;
        }

        // Amount risk
        if (($indicators['amount_anomaly'] ?? false)) {
            $baseScore += 20;
        }

        // Velocity risk
        if (($indicators['velocity_anomaly'] ?? false)) {
            $baseScore += 20;
        }

        // Relationship risk
        if (($indicators['relationship_anomaly'] ?? false)) {
            $baseScore += 20;
        }

        // Cap at 100
        $riskScore = min(100, $baseScore);
        
        $this->update([
            'risk_score' => $riskScore,
            'risk_level' => $this->calculateRiskLevel($riskScore)
        ]);

        return $riskScore;
    }

    public function calculateRiskLevel(float $score): string
    {
        if ($score >= 80) return 'critical';
        if ($score >= 60) return 'high';
        if ($score >= 40) return 'medium';
        if ($score >= 20) return 'low';
        return 'minimal';
    }

    public function markAsInvestigating(int $investigatorId): void
    {
        $this->update([
            'status' => 'investigating',
            'investigated_by' => $investigatorId,
            'investigated_at' => now()
        ]);
    }

    public function markAsResolved(string $resolution, array $actions = []): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'investigation_notes' => $resolution,
            'mitigation_actions' => $actions
        ]);
    }

    public function markAsFalsePositive(string $reason): void
    {
        $this->update([
            'status' => 'false_positive',
            'resolved_at' => now(),
            'investigation_notes' => $reason
        ]);
    }

    public function generateComplianceFlags(): array
    {
        $flags = [];

        if ($this->risk_score >= 80) {
            $flags[] = 'high_risk_transaction';
        }

        if ($this->fraud_type === 'money_laundering') {
            $flags[] = 'aml_red_flag';
        }

        if ($this->fraud_type === 'identity_theft') {
            $flags[] = 'kyc_verification_required';
        }

        $this->update(['compliance_flags' => $flags]);
        return $flags;
    }

    public function shouldReportToRegulator(): bool
    {
        return $this->risk_score >= 70 || 
               $this->fraud_type === 'money_laundering' ||
               $this->fraud_type === 'identity_theft';
    }

    // AI Analysis Methods
    protected function detectLocationAnomaly(): bool
    {
        $locationData = $this->location_analysis ?? [];
        $userHistory = $this->user->transactions()->pluck('location')->toArray();
        
        // Simple anomaly detection - can be enhanced with ML
        $currentLocation = $locationData['current_location'] ?? null;
        $usualLocations = array_unique($userHistory);
        
        return !in_array($currentLocation, $usualLocations);
    }

    protected function detectDeviceAnomaly(): bool
    {
        $deviceData = $this->device_analysis ?? [];
        $userDevices = $this->user->pushNotificationDevices()->pluck('device_token')->toArray();
        
        $currentDevice = $deviceData['device_token'] ?? null;
        return !in_array($currentDevice, $userDevices);
    }

    protected function detectTemporalAnomaly(): bool
    {
        $temporalData = $this->temporal_patterns ?? [];
        $currentHour = now()->hour;
        
        // Detect unusual transaction times (e.g., 2-5 AM)
        return $currentHour >= 2 && $currentHour <= 5;
    }

    protected function detectAmountAnomaly(): bool
    {
        $amountData = $this->amount_patterns ?? [];
        $userAvgAmount = $this->user->transactions()->avg('amount') ?? 0;
        $currentAmount = $amountData['current_amount'] ?? 0;
        
        // Detect amounts significantly higher than user's average
        return $currentAmount > ($userAvgAmount * 5);
    }

    protected function detectVelocityAnomaly(): bool
    {
        $velocityData = $this->velocity_patterns ?? [];
        $recentTransactions = $this->user->transactions()
            ->where('created_at', '>=', now()->subHours(1))
            ->count();
        
        // Detect unusually high transaction frequency
        return $recentTransactions > 10;
    }

    protected function detectRelationshipAnomaly(): bool
    {
        $relationshipData = $this->relationship_analysis ?? [];
        $suspiciousConnections = $relationshipData['suspicious_connections'] ?? [];
        
        return !empty($suspiciousConnections);
    }

    // Scopes
    public function scopeCritical($query)
    {
        return $query->where('risk_level', 'critical');
    }

    public function scopeHighRisk($query)
    {
        return $query->whereIn('risk_level', ['high', 'critical']);
    }

    public function scopeUnderInvestigation($query)
    {
        return $query->where('status', 'investigating');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByFraudType($query, string $type)
    {
        return $query->where('fraud_type', $type);
    }

    public function scopeByRiskLevel($query, string $level)
    {
        return $query->where('risk_level', $level);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('detected_at', [$startDate, $endDate]);
    }

    // Static Methods
    public static function getStatistics(): array
    {
        return [
            'total_detections' => self::count(),
            'critical_detections' => self::critical()->count(),
            'high_risk_detections' => self::highRisk()->count(),
            'under_investigation' => self::underInvestigation()->count(),
            'resolved_detections' => self::whereIn('status', ['resolved', 'false_positive'])->count(),
            'fraud_types' => self::selectRaw('fraud_type, COUNT(*) as count')
                ->groupBy('fraud_type')
                ->pluck('count', 'fraud_type')
                ->toArray(),
            'risk_levels' => self::selectRaw('risk_level, COUNT(*) as count')
                ->groupBy('risk_level')
                ->pluck('count', 'risk_level')
                ->toArray(),
            'average_risk_score' => self::avg('risk_score'),
            'detection_rate' => self::where('status', 'confirmed')->count() / max(self::count(), 1) * 100
        ];
    }

    public static function getFraudTrends(): array
    {
        $last30Days = now()->subDays(30);
        
        return [
            'daily_detections' => self::where('detected_at', '>=', $last30Days)
                ->selectRaw('DATE(detected_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('count', 'date')
                ->toArray(),
            'fraud_type_trends' => self::where('detected_at', '>=', $last30Days)
                ->selectRaw('fraud_type, COUNT(*) as count')
                ->groupBy('fraud_type')
                ->pluck('count', 'fraud_type')
                ->toArray(),
            'risk_level_trends' => self::where('detected_at', '>=', $last30Days)
                ->selectRaw('risk_level, COUNT(*) as count')
                ->groupBy('risk_level')
                ->pluck('count', 'risk_level')
                ->toArray()
        ];
    }
}
