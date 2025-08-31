<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cardTypes = ['visa', 'mastercard', 'mada'];
        $cardType = $this->faker->randomElement($cardTypes);
        
        return [
            'user_id' => User::factory(),
            'card_number_hash' => hash('sha256', $this->faker->creditCardNumber($cardType)),
            'card_type' => $cardType,
            'expiry_date' => $this->faker->date('m/y', '+2 years'),
            'cardholder_name' => $this->faker->name(),
            'is_active' => $this->faker->boolean(90),
            'is_default' => false,
        ];
    }

    /**
     * Indicate that the card is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the card is the default card.
     */
    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }

    /**
     * Indicate that the card is a Visa card.
     */
    public function visa(): static
    {
        return $this->state(fn (array $attributes) => [
            'card_type' => 'visa',
        ]);
    }

    /**
     * Indicate that the card is a Mastercard.
     */
    public function mastercard(): static
    {
        return $this->state(fn (array $attributes) => [
            'card_type' => 'mastercard',
        ]);
    }

    /**
     * Indicate that the card is a Mada card.
     */
    public function mada(): static
    {
        return $this->state(fn (array $attributes) => [
            'card_type' => 'mada',
        ]);
    }
}
