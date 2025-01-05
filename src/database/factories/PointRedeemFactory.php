<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PointRedeemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'owner_id' => User::factory()->create()->id,
            'points' => fake()->numberBetween(50, 2500) * 100,
            'redeemed_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
