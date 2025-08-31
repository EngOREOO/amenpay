<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
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
            'type' => $this->faker->randomElement(['info', 'success', 'warning', 'error', 'transaction', 'security']),
            'title_ar' => $this->faker->sentence(3),
            'title_en' => $this->faker->sentence(3),
            'message_ar' => $this->faker->paragraph(2),
            'message_en' => $this->faker->paragraph(2),
            'data' => $this->faker->optional()->json(),
            'is_read' => $this->faker->boolean(30),
            'read_at' => $this->faker->optional(0.3)->dateTime(),
        ];
    }

    /**
     * Indicate that the notification is unread.
     */
    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Indicate that the notification is read.
     */
    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Indicate that the notification is a transaction notification.
     */
    public function transaction(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'transaction',
        ]);
    }

    /**
     * Indicate that the notification is a security notification.
     */
    public function security(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'security',
        ]);
    }
}
