<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PushNotification>
 */
class PushNotificationFactory extends Factory
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
            'device_token' => $this->faker->unique()->regexify('[A-Za-z0-9]{64}'),
            'platform' => $this->faker->randomElement(['ios', 'android', 'web']),
            'app_version' => $this->faker->semver(),
            'device_model' => $this->faker->randomElement(['iPhone 12', 'Samsung Galaxy S21', 'Google Pixel 6', 'Chrome Browser']),
            'os_version' => $this->faker->randomElement(['15.0', '12.0', '11.0', 'Windows 11']),
            'status' => $this->faker->randomElement(['active', 'inactive', 'expired']),
            'last_used_at' => $this->faker->optional(0.8)->dateTime(),
            'expires_at' => $this->faker->optional(0.2)->dateTime('+1 year'),
            'preferences' => [
                'transaction_notifications' => $this->faker->boolean(80),
                'budget_alerts' => $this->faker->boolean(70),
                'goal_updates' => $this->faker->boolean(60),
                'security_alerts' => $this->faker->boolean(90),
                'promotional_notifications' => $this->faker->boolean(30),
            ],
        ];
    }

    /**
     * Indicate that the device is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the device is iOS.
     */
    public function ios(): static
    {
        return $this->state(fn (array $attributes) => [
            'platform' => 'ios',
            'device_model' => $this->faker->randomElement(['iPhone 12', 'iPhone 13', 'iPhone 14', 'iPhone 15']),
            'os_version' => $this->faker->randomElement(['15.0', '16.0', '17.0', '18.0']),
        ]);
    }

    /**
     * Indicate that the device is Android.
     */
    public function android(): static
    {
        return $this->state(fn (array $attributes) => [
            'platform' => 'android',
            'device_model' => $this->faker->randomElement(['Samsung Galaxy S21', 'Google Pixel 6', 'OnePlus 9', 'Xiaomi 12']),
            'os_version' => $this->faker->randomElement(['11.0', '12.0', '13.0', '14.0']),
        ]);
    }

    /**
     * Indicate that the device is web-based.
     */
    public function web(): static
    {
        return $this->state(fn (array $attributes) => [
            'platform' => 'web',
            'device_model' => $this->faker->randomElement(['Chrome Browser', 'Firefox Browser', 'Safari Browser', 'Edge Browser']),
            'os_version' => $this->faker->randomElement(['Windows 11', 'macOS 14', 'Ubuntu 22.04', 'Chrome OS']),
        ]);
    }
}
