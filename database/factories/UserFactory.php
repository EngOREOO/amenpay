<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone' => '+966' . $this->faker->numberBetween(500000000, 599999999),
            'email' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
            'national_id' => $this->faker->unique()->numerify('##########'),
            'avatar' => null,
            'language' => $this->faker->randomElement(['ar', 'en']),
            'is_verified' => $this->faker->boolean(80),
            'email_verified_at' => $this->faker->optional(0.7)->dateTime(),
            'phone_verified_at' => $this->faker->optional(0.8)->dateTime(),
            'last_login_at' => $this->faker->optional(0.6)->dateTime(),
            'status' => $this->faker->randomElement(['active', 'suspended', 'blocked']),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => true,
            'phone_verified_at' => now(),
        ]);
    }

    /**
     * Indicate that the user is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the user speaks Arabic.
     */
    public function arabic(): static
    {
        return $this->state(fn (array $attributes) => [
            'language' => 'ar',
        ]);
    }

    /**
     * Indicate that the user speaks English.
     */
    public function english(): static
    {
        return $this->state(fn (array $attributes) => [
            'language' => 'en',
        ]);
    }
}
