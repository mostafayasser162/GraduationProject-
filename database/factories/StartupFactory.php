<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Startup>
 */
class StartupFactory extends Factory
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
            'name' => $this->faker->company,
            'description' => $this->faker->paragraph,
            'logo' => $this->faker->imageUrl(200, 200, 'business'),
            'social_media_links' => json_encode([
                'facebook' => $this->faker->url,
                'twitter' => $this->faker->url,
                'linkedin' => $this->faker->url,
            ]),
            'phone' => $this->faker->phoneNumber,
            'status' => $this->faker->randomElement(['pending', 'active', 'suspended']),
            'package_id' => $this->faker->numberBetween(1, 5),
            'categories_id' => $this->faker->numberBetween(1, 4),
        ];
    }
}
