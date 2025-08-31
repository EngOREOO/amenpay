<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'status',
        'configuration',
        'supported_currencies',
        'supported_payment_methods',
        'transaction_fee_percentage',
        'transaction_fee_fixed',
        'min_transaction_amount',
        'max_transaction_amount',
        'supported_banks',
        'webhook_endpoints',
        'supports_refunds',
        'supports_partial_refunds',
        'refund_days_limit',
        'api_endpoints',
        'environment',
        'last_health_check',
        'health_status',
        'metadata'
    ];

    protected $casts = [
        'supported_currencies' => 'array',
        'supported_payment_methods' => 'array',
        'supported_banks' => 'array',
        'webhook_endpoints' => 'array',
        'api_endpoints' => 'array',
        'configuration' => 'array',
        'metadata' => 'array',
        'last_health_check' => 'datetime',
        'transaction_fee_percentage' => 'decimal:4',
        'transaction_fee_fixed' => 'decimal:2',
        'min_transaction_amount' => 'integer',
        'max_transaction_amount' => 'integer',
        'supports_refunds' => 'boolean',
        'supports_partial_refunds' => 'boolean',
        'refund_days_limit' => 'integer'
    ];

    protected $appends = [
        'is_active',
        'is_healthy',
        'total_transactions',
        'success_rate',
        'average_transaction_amount'
    ];

    /**
     * Get the payment transactions for this gateway.
     */
    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    /**
     * Get the webhooks for this gateway.
     */
    public function webhooks(): HasMany
    {
        return $this->hasMany(PaymentWebhook::class);
    }

    /**
     * Check if gateway is active.
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if gateway is healthy.
     */
    public function getIsHealthyAttribute(): bool
    {
        return $this->health_status === 'healthy';
    }

    /**
     * Get total transactions count.
     */
    public function getTotalTransactionsAttribute(): int
    {
        return $this->paymentTransactions()->count();
    }

    /**
     * Get success rate percentage.
     */
    public function getSuccessRateAttribute(): float
    {
        $total = $this->paymentTransactions()->count();
        if ($total === 0) return 0;

        $successful = $this->paymentTransactions()
            ->whereIn('status', ['completed', 'refunded', 'partially_refunded'])
            ->count();

        return round(($successful / $total) * 100, 2);
    }

    /**
     * Get average transaction amount.
     */
    public function getAverageTransactionAmountAttribute(): float
    {
        $avg = $this->paymentTransactions()
            ->where('status', 'completed')
            ->avg('amount');

        return round($avg ?? 0, 2);
    }

    /**
     * Check if gateway supports specific currency.
     */
    public function supportsCurrency(string $currency): bool
    {
        return in_array(strtoupper($currency), $this->supported_currencies ?? ['SAR']);
    }

    /**
     * Check if gateway supports specific payment method.
     */
    public function supportsPaymentMethod(string $method): bool
    {
        return in_array($method, $this->supported_payment_methods ?? []);
    }

    /**
     * Check if gateway supports specific bank.
     */
    public function supportsBank(string $bankCode): bool
    {
        if (!$this->supported_banks) return false;
        return in_array($bankCode, $this->supported_banks);
    }

    /**
     * Check if gateway supports refunds.
     */
    public function supportsRefunds(): bool
    {
        return $this->supports_refunds;
    }

    /**
     * Check if gateway supports partial refunds.
     */
    public function supportsPartialRefunds(): bool
    {
        return $this->supports_partial_refunds;
    }

    /**
     * Calculate transaction fees.
     */
    public function calculateFees(float $amount): array
    {
        $percentageFee = ($amount * $this->transaction_fee_percentage) / 100;
        $fixedFee = $this->transaction_fee_fixed;
        $totalFee = $percentageFee + $fixedFee;
        $netAmount = $amount - $totalFee;

        return [
            'amount' => $amount,
            'percentage_fee' => round($percentageFee, 2),
            'fixed_fee' => $fixedFee,
            'total_fee' => round($totalFee, 2),
            'net_amount' => round($netAmount, 2)
        ];
    }

    /**
     * Validate transaction amount.
     */
    public function validateAmount(float $amount): array
    {
        $errors = [];

        if ($amount < $this->min_transaction_amount) {
            $errors[] = "Amount must be at least SAR {$this->min_transaction_amount}";
        }

        if ($amount > $this->max_transaction_amount) {
            $errors[] = "Amount cannot exceed SAR {$this->max_transaction_amount}";
        }

        return [
            'is_valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Get API endpoint for current environment.
     */
    public function getApiEndpoint(string $endpointType = 'base'): ?string
    {
        if (!$this->api_endpoints) return null;

        $environment = $this->environment;
        $endpoints = $this->api_endpoints;

        return $endpoints[$environment][$endpointType] ?? $endpoints[$environment]['base'] ?? null;
    }

    /**
     * Get webhook endpoint for specific event.
     */
    public function getWebhookEndpoint(string $eventType): ?string
    {
        if (!$this->webhook_endpoints) return null;

        return $this->webhook_endpoints[$eventType] ?? null;
    }

    /**
     * Get configuration value.
     */
    public function getConfig(string $key, $default = null)
    {
        return data_get($this->configuration, $key, $default);
    }

    /**
     * Set configuration value.
     */
    public function setConfig(string $key, $value): void
    {
        $config = $this->configuration ?? [];
        $config[$key] = $value;
        $this->update(['configuration' => $config]);
    }

    /**
     * Update health status.
     */
    public function updateHealthStatus(string $status, ?string $error = null): void
    {
        $this->update([
            'health_status' => $status,
            'last_health_check' => now(),
            'metadata' => array_merge($this->metadata ?? [], [
                'last_health_check_error' => $error,
                'last_health_check_at' => now()->toISOString()
            ])
        ]);
    }

    /**
     * Check if gateway is in maintenance.
     */
    public function isInMaintenance(): bool
    {
        return $this->status === 'maintenance';
    }

    /**
     * Check if gateway is deprecated.
     */
    public function isDeprecated(): bool
    {
        return $this->status === 'deprecated';
    }

    /**
     * Get gateway summary.
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
            'status' => $this->status,
            'environment' => $this->environment,
            'health_status' => $this->health_status,
            'is_active' => $this->is_active,
            'is_healthy' => $this->is_healthy,
            'total_transactions' => $this->total_transactions,
            'success_rate' => $this->success_rate,
            'average_transaction_amount' => $this->average_transaction_amount,
            'last_health_check' => $this->last_health_check?->diffForHumans()
        ];
    }

    /**
     * Scope for active gateways.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for healthy gateways.
     */
    public function scopeHealthy($query)
    {
        return $query->where('health_status', 'healthy');
    }

    /**
     * Scope for gateways by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for gateways by environment.
     */
    public function scopeByEnvironment($query, $environment)
    {
        return $query->where('environment', $environment);
    }

    /**
     * Scope for gateways supporting specific currency.
     */
    public function scopeSupportingCurrency($query, $currency)
    {
        return $query->whereJsonContains('supported_currencies', strtoupper($currency));
    }

    /**
     * Scope for gateways supporting specific payment method.
     */
    public function scopeSupportingPaymentMethod($query, $method)
    {
        return $query->whereJsonContains('supported_payment_methods', $method);
    }

    /**
     * Get gateway statistics.
     */
    public static function getStatistics(): array
    {
        $total = static::count();
        $active = static::active()->count();
        $healthy = static::healthy()->count();
        $inMaintenance = static::where('status', 'maintenance')->count();

        $byType = static::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        $byEnvironment = static::selectRaw('environment, COUNT(*) as count')
            ->groupBy('environment')
            ->pluck('count', 'environment')
            ->toArray();

        return [
            'total_gateways' => $total,
            'active_gateways' => $active,
            'healthy_gateways' => $healthy,
            'in_maintenance' => $inMaintenance,
            'gateways_by_type' => $byType,
            'gateways_by_environment' => $byEnvironment,
            'health_rate' => $total > 0 ? round(($healthy / $total) * 100, 2) : 0
        ];
    }

    /**
     * Create Saudi payment gateway.
     */
    public static function createSaudiGateway(
        string $name,
        string $code,
        string $type = 'saudi_gateway',
        array $configuration = [],
        array $supportedMethods = []
    ): self {
        return static::create([
            'name' => $name,
            'code' => $code,
            'type' => $type,
            'status' => 'active',
            'configuration' => $configuration,
            'supported_currencies' => ['SAR'],
            'supported_payment_methods' => $supportedMethods,
            'environment' => 'sandbox',
            'health_status' => 'unknown'
        ]);
    }

    /**
     * Create STC Pay gateway.
     */
    public static function createStcPayGateway(): self
    {
        return static::createSaudiGateway(
            'STC Pay',
            'stc_pay',
            'mobile_wallet',
            [
                'merchant_id' => config('services.stc_pay.merchant_id'),
                'api_key' => config('services.stc_pay.api_key'),
                'secret_key' => config('services.stc_pay.secret_key')
            ],
            ['mobile_wallet', 'qr_code']
        );
    }

    /**
     * Create mada gateway.
     */
    public static function createMadaGateway(): self
    {
        return static::createSaudiGateway(
            'mada',
            'mada',
            'saudi_gateway',
            [
                'merchant_id' => config('services.mada.merchant_id'),
                'terminal_id' => config('services.mada.terminal_id'),
                'secret_key' => config('services.mada.secret_key')
            ],
            ['credit_card', 'debit_card']
        );
    }

    /**
     * Create Apple Pay gateway.
     */
    public static function createApplePayGateway(): self
    {
        return static::createSaudiGateway(
            'Apple Pay',
            'apple_pay',
            'mobile_wallet',
            [
                'merchant_id' => config('services.apple_pay.merchant_id'),
                'certificate_path' => config('services.apple_pay.certificate_path'),
                'private_key_path' => config('services.apple_pay.private_key_path')
            ],
            ['apple_pay']
        );
    }

    /**
     * Clean up deprecated gateways.
     */
    public static function cleanupDeprecated(): int
    {
        return static::where('status', 'deprecated')
            ->where('updated_at', '<', now()->subMonths(6))
            ->delete();
    }
}
