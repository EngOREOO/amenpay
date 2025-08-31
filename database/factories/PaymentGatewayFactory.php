<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentGateway>
 */
class PaymentGatewayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . ' Gateway',
            'code' => $this->faker->unique()->slug(2),
            'type' => $this->faker->randomElement(['saudi_gateway', 'international', 'bank_api', 'mobile_wallet']),
            'status' => $this->faker->randomElement(['active', 'inactive', 'maintenance', 'deprecated']),
            'configuration' => [
                'merchant_id' => $this->faker->uuid(),
                'api_key' => $this->faker->regexify('[A-Za-z0-9]{32}'),
                'secret_key' => $this->faker->regexify('[A-Za-z0-9]{64}'),
            ],
            'supported_currencies' => $this->faker->randomElements(['SAR', 'USD', 'EUR', 'GBP'], $this->faker->numberBetween(1, 4)),
            'supported_payment_methods' => $this->faker->randomElements(['credit_card', 'debit_card', 'mobile_wallet', 'qr_code'], $this->faker->numberBetween(1, 4)),
            'transaction_fee_percentage' => $this->faker->randomFloat(4, 0, 5),
            'transaction_fee_fixed' => $this->faker->randomFloat(2, 0, 10),
            'min_transaction_amount' => $this->faker->numberBetween(1, 100),
            'max_transaction_amount' => $this->faker->numberBetween(1000, 50000),
            'supported_banks' => $this->faker->optional()->randomElements(['SABB', 'AlRajhi', 'NCB', 'Riyad Bank'], $this->faker->numberBetween(1, 4)),
            'webhook_endpoints' => $this->faker->optional()->json(),
            'supports_refunds' => $this->faker->boolean(80),
            'supports_partial_refunds' => $this->faker->boolean(60),
            'refund_days_limit' => $this->faker->numberBetween(7, 90),
            'api_endpoints' => $this->faker->optional()->json(),
            'environment' => $this->faker->randomElement(['sandbox', 'production']),
            'last_health_check' => $this->faker->optional(0.7)->dateTime(),
            'health_status' => $this->faker->randomElement(['healthy', 'warning', 'error', 'unknown']),
            'metadata' => $this->faker->optional()->json(),
        ];
    }

    /**
     * Indicate that the gateway is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the gateway is a Saudi gateway.
     */
    public function saudi(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'saudi_gateway',
            'supported_currencies' => ['SAR'],
        ]);
    }

    /**
     * Indicate that the gateway is international.
     */
    public function international(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'international',
            'supported_currencies' => ['USD', 'EUR', 'GBP'],
        ]);
    }

    /**
     * Indicate that the gateway is healthy.
     */
    public function healthy(): static
    {
        return $this->state(fn (array $attributes) => [
            'health_status' => 'healthy',
        ]);
    }

    /**
     * Indicate that the gateway is in sandbox mode.
     */
    public function sandbox(): static
    {
        return $this->state(fn (array $attributes) => [
            'environment' => 'sandbox',
        ]);
    }
}
