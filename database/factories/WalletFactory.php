<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallet>
 */
class WalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
                             'wallet_number' => 'WAL' . $this->faker->unique()->numerify('########'),
            'balance' => $this->faker->randomFloat(2, 0, 10000),
            'currency' => 'SAR',
            'status' => $this->faker->randomElement(['active', 'suspended', 'closed']),
        ];
    }

    /**
     * Indicate that the wallet is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the wallet has a specific balance.
     */
    public function withBalance(float $balance): static
    {
        return $this->state(fn (array $attributes) => [
            'balance' => $balance,
        ]);
    }

    /**
     * Indicate that the wallet is empty.
     */
    public function empty(): static
    {
        return $this->state(fn (array $attributes) => [
            'balance' => 0.00,
        ]);
    }
}
