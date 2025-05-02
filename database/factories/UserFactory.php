<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' =>  Str::random(10),
            'role' => $this->faker->randomElement(['USER', 'ADMIN', 'OWNER', 'INVESTOR']),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
