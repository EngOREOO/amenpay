<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KycVerification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'verification_type',
        'status',
        'verification_level',
        'identity_documents',
        'address_proof',
        'income_documents',
        'source_of_funds',
        'business_documents',
        'compliance_checks',
        'risk_assessment',
        'verification_steps',
        'submitted_at',
        'reviewed_at',
        'approved_at',
        'expires_at',
        'reviewed_by',
        'rejection_reason',
        'additional_requirements',
        'aml_flags',
        'sanctions_matches',
        'pep_checks',
        'adverse_media',
        'regulatory_flags'
    ];

    protected $casts = [
        'identity_documents' => 'array',
        'address_proof' => 'array',
        'income_documents' => 'array',
        'source_of_funds' => 'array',
        'business_documents' => 'array',
        'compliance_checks' => 'array',
        'risk_assessment' => 'array',
        'verification_steps' => 'array',
        'additional_requirements' => 'array',
        'aml_flags' => 'array',
        'sanctions_matches' => 'array',
        'pep_checks' => 'array',
        'adverse_media' => 'array',
        'regulatory_flags' => 'array',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    protected $appends = [
        'is_expired',
        'is_approved',
        'is_rejected',
        'is_pending',
        'days_until_expiry',
        'verification_age',
        'completion_percentage'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function regulatoryReports(): HasMany
    {
        return $this->hasMany(RegulatoryReport::class);
    }

    // Accessors
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getIsApprovedAttribute(): bool
    {
        return $this->status === 'approved';
    }

    public function getIsRejectedAttribute(): bool
    {
        return $this->status === 'rejected';
    }

    public function getIsPendingAttribute(): bool
    {
        return in_array($this->status, ['pending', 'submitted', 'reviewing']);
    }

    public function getDaysUntilExpiryAttribute(): int
    {
        if (!$this->expires_at) return 0;
        return max(0, now()->diffInDays($this->expires_at, false));
    }

    public function getVerificationAgeAttribute(): int
    {
        return $this->submitted_at ? now()->diffInDays($this->submitted_at) : 0;
    }

    public function getCompletionPercentageAttribute(): int
    {
        $steps = $this->verification_steps ?? [];
        $totalSteps = $this->getTotalVerificationSteps();
        $completedSteps = count(array_filter($steps, fn($step) => $step['completed'] ?? false));
        
        return $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;
    }

    // Business Logic Methods
    public function submitForReview(): void
    {
        $this->update([
            'status' => 'submitted',
            'submitted_at' => now()
        ]);
    }

    public function startReview(int $reviewerId): void
    {
        $this->update([
            'status' => 'reviewing',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now()
        ]);
    }

    public function approve(): void
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now()
        ]);

        // Update user verification status
        $this->user->update([
            'is_verified' => true,
            'phone_verified_at' => now()
        ]);
    }

    public function reject(string $reason, array $requirements = []): void
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'additional_requirements' => $requirements
        ]);
    }

    public function addVerificationStep(string $step, bool $completed = false, array $data = []): void
    {
        $steps = $this->verification_steps ?? [];
        $steps[] = [
            'step' => $step,
            'completed' => $completed,
            'data' => $data,
            'timestamp' => now()->toISOString()
        ];

        $this->update(['verification_steps' => $steps]);
    }

    public function completeVerificationStep(string $step, array $data = []): void
    {
        $steps = $this->verification_steps ?? [];
        
        foreach ($steps as &$stepData) {
            if ($stepData['step'] === $step) {
                $stepData['completed'] = true;
                $stepData['data'] = array_merge($stepData['data'] ?? [], $data);
                $stepData['completed_at'] = now()->toISOString();
                break;
            }
        }

        $this->update(['verification_steps' => $steps]);
    }

    public function performComplianceChecks(): array
    {
        $checks = [
            'aml_screening' => $this->performAMLScreening(),
            'sanctions_screening' => $this->performSanctionsScreening(),
            'pep_screening' => $this->performPEPScreening(),
            'adverse_media_screening' => $this->performAdverseMediaScreening(),
            'regulatory_screening' => $this->performRegulatoryScreening()
        ];

        $this->update(['compliance_checks' => $checks]);
        return $checks;
    }

    public function calculateRiskScore(): float
    {
        $baseScore = 0;
        $checks = $this->compliance_checks ?? [];

        // AML risk
        if (($checks['aml_screening']['risk_level'] ?? 'low') === 'high') {
            $baseScore += 30;
        }

        // Sanctions risk
        if (!empty($checks['sanctions_screening']['matches'] ?? [])) {
            $baseScore += 40;
        }

        // PEP risk
        if (($checks['pep_screening']['is_pep'] ?? false)) {
            $baseScore += 25;
        }

        // Adverse media risk
        if (!empty($checks['adverse_media_screening']['negative_coverage'] ?? [])) {
            $baseScore += 20;
        }

        // Regulatory risk
        if (($checks['regulatory_screening']['risk_level'] ?? 'low') === 'high') {
            $baseScore += 25;
        }

        $riskScore = min(100, $baseScore);
        
        $this->update([
            'risk_assessment' => [
                'risk_score' => $riskScore,
                'risk_level' => $this->calculateRiskLevel($riskScore),
                'risk_factors' => $this->identifyRiskFactors($checks)
            ]
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

    public function identifyRiskFactors(array $checks): array
    {
        $factors = [];

        if (($checks['aml_screening']['risk_level'] ?? 'low') === 'high') {
            $factors[] = 'high_aml_risk';
        }

        if (!empty($checks['sanctions_screening']['matches'] ?? [])) {
            $factors[] = 'sanctions_match';
        }

        if (($checks['pep_screening']['is_pep'] ?? false)) {
            $factors[] = 'politically_exposed_person';
        }

        if (!empty($checks['adverse_media_screening']['negative_coverage'] ?? [])) {
            $factors[] = 'adverse_media_coverage';
        }

        return $factors;
    }

    public function generateRegulatoryFlags(): array
    {
        $flags = [];
        $checks = $this->compliance_checks ?? [];

        if (($checks['aml_screening']['risk_level'] ?? 'low') === 'high') {
            $flags[] = 'aml_enhanced_due_diligence';
        }

        if (!empty($checks['sanctions_screening']['matches'] ?? [])) {
            $flags[] = 'sanctions_screening_required';
        }

        if (($checks['pep_screening']['is_pep'] ?? false)) {
            $flags[] = 'pep_enhanced_monitoring';
        }

        if (($checks['regulatory_screening']['risk_level'] ?? 'low') === 'high') {
            $flags[] = 'regulatory_oversight_required';
        }

        $this->update(['regulatory_flags' => $flags]);
        return $flags;
    }

    public function shouldRequireEnhancedDueDiligence(): bool
    {
        $riskAssessment = $this->risk_assessment ?? [];
        return ($riskAssessment['risk_level'] ?? 'low') === 'high';
    }

    public function isCompliant(): bool
    {
        $checks = $this->compliance_checks ?? [];
        
        // Check if all critical checks pass
        $criticalChecks = [
            'aml_screening' => $checks['aml_screening']['status'] ?? 'pending',
            'sanctions_screening' => $checks['sanctions_screening']['status'] ?? 'pending',
            'pep_screening' => $checks['pep_screening']['status'] ?? 'pending'
        ];

        return !in_array('failed', $criticalChecks);
    }

    // Compliance Check Methods
    protected function performAMLScreening(): array
    {
        // Simulate AML screening - in production, integrate with AML service
        $userData = $this->user->only(['name', 'national_id', 'phone']);
        
        return [
            'status' => 'completed',
            'risk_level' => 'low',
            'screening_date' => now()->toISOString(),
            'screened_entities' => $userData,
            'risk_factors' => []
        ];
    }

    protected function performSanctionsScreening(): array
    {
        // Simulate sanctions screening - in production, integrate with sanctions service
        return [
            'status' => 'completed',
            'matches' => [],
            'screening_date' => now()->toISOString(),
            'screened_lists' => ['UN', 'EU', 'US', 'Saudi']
        ];
    }

    protected function performPEPScreening(): array
    {
        // Simulate PEP screening - in production, integrate with PEP service
        return [
            'status' => 'completed',
            'is_pep' => false,
            'pep_details' => null,
            'screening_date' => now()->toISOString()
        ];
    }

    protected function performAdverseMediaScreening(): array
    {
        // Simulate adverse media screening - in production, integrate with media monitoring service
        return [
            'status' => 'completed',
            'negative_coverage' => [],
            'screening_date' => now()->toISOString()
        ];
    }

    protected function performRegulatoryScreening(): array
    {
        // Simulate regulatory screening - in production, integrate with regulatory service
        return [
            'status' => 'completed',
            'risk_level' => 'low',
            'screening_date' => now()->toISOString(),
            'regulatory_requirements' => ['SAMA', 'AML', 'KYC']
        ];
    }

    protected function getTotalVerificationSteps(): int
    {
        $verificationType = $this->verification_type;
        
        $stepMap = [
            'identity' => 3, // Document upload, verification, approval
            'address' => 2,  // Proof upload, verification
            'income' => 2,   // Document upload, verification
            'source_of_funds' => 2, // Declaration, verification
            'business' => 4  // Registration, licenses, verification, approval
        ];

        return $stepMap[$verificationType] ?? 3;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->whereIn('status', ['pending', 'submitted']);
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'reviewing');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeByVerificationType($query, string $type)
    {
        return $query->where('verification_type', $type);
    }

    public function scopeByRiskLevel($query, string $level)
    {
        return $query->whereJsonContains('risk_assessment->risk_level', $level);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('submitted_at', [$startDate, $endDate]);
    }

    // Static Methods
    public static function getStatistics(): array
    {
        return [
            'total_verifications' => self::count(),
            'pending_verifications' => self::pending()->count(),
            'under_review' => self::underReview()->count(),
            'approved_verifications' => self::approved()->count(),
            'rejected_verifications' => self::rejected()->count(),
            'expired_verifications' => self::expired()->count(),
            'verification_types' => self::selectRaw('verification_type, COUNT(*) as count')
                ->groupBy('verification_type')
                ->pluck('count', 'verification_type')
                ->toArray(),
            'risk_levels' => self::selectRaw('JSON_EXTRACT(risk_assessment, "$.risk_level") as risk_level, COUNT(*) as count')
                ->whereNotNull('risk_assessment')
                ->groupBy('risk_level')
                ->pluck('count', 'risk_level')
                ->toArray(),
            'average_completion_time' => self::whereNotNull('approved_at')
                ->whereNotNull('submitted_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(DAY, submitted_at, approved_at)) as avg_days')
                ->first()?->avg_days ?? 0
        ];
    }

    public static function getVerificationTrends(): array
    {
        $last30Days = now()->subDays(30);
        
        return [
            'daily_submissions' => self::where('submitted_at', '>=', $last30Days)
                ->selectRaw('DATE(submitted_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('count', 'date')
                ->toArray(),
            'verification_type_trends' => self::where('submitted_at', '>=', $last30Days)
                ->selectRaw('verification_type, COUNT(*) as count')
                ->groupBy('verification_type')
                ->pluck('count', 'verification_type')
                ->toArray(),
            'approval_rate' => self::where('submitted_at', '>=', $last30Days)
                ->selectRaw('COUNT(CASE WHEN status = "approved" THEN 1 END) * 100.0 / COUNT(*) as approval_rate')
                ->first()?->approval_rate ?? 0
        ];
    }
}
