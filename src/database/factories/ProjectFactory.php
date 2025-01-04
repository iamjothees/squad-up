<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->text(100),
            'service_id' => Service::factory()->create()->id,
            'admin_id' => User::factory()->teamMember()->create()->id,
            'start_at' => fake()->date(),
            'completion_at' => fn (array $attributes) => fake()->dateTimeBetween($attributes['start_at'], Carbon::parse($attributes['start_at'])->addMonths(3)),
            'deliver_at' => fn (array $attributes) => fake()->dateTimeBetween($attributes['completion_at'], Carbon::parse($attributes['completion_at'])->addWeek()),
            'committed_budget' => fake()->numberBetween(1000, 10000),
            'initial_payment' => fn (array $attributes) => $attributes['committed_budget'] / 2,
            'priority_level' => fake()->numberBetween(1, 10),
        ];
    }
}
