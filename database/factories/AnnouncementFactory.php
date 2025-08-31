<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title_ar' => $this->faker->sentence(4),
            'title_en' => $this->faker->sentence(4),
            'content_ar' => $this->faker->paragraphs(3, true),
            'content_en' => $this->faker->paragraphs(3, true),
            'type' => $this->faker->randomElement(['info', 'success', 'warning', 'error', 'maintenance']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'urgent']),
            'status' => $this->faker->randomElement(['draft', 'active', 'scheduled', 'expired']),
            'published_at' => $this->faker->optional(0.8)->dateTime(),
            'expires_at' => $this->faker->optional(0.3)->dateTime('+1 month'),
            'target_audience' => $this->faker->optional()->json(),
            'delivery_channels' => $this->faker->randomElements(['push', 'in_app', 'sms', 'email'], $this->faker->numberBetween(1, 4)),
            'requires_acknowledgment' => $this->faker->boolean(30),
            'acknowledged_count' => $this->faker->optional(0.7)->numberBetween(0, 1000),
            'metadata' => $this->faker->optional()->json(),
        ];
    }

    /**
     * Indicate that the announcement is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'published_at' => now(),
        ]);
    }

    /**
     * Indicate that the announcement is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'published_at' => now(),
        ]);
    }

    /**
     * Indicate that the announcement is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the announcement is urgent.
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'urgent',
        ]);
    }

    /**
     * Indicate that the announcement is a maintenance notice.
     */
    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'maintenance',
        ]);
    }
}
