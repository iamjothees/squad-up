<?php

namespace Database\Factories;

use App\Enums\ServiceStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->boolean() ? null : fake()->text(100),
            'status' => fake()->randomElement(ServiceStatus::cases()),
        ];
    }

    public function active(): self{
        return $this->state(fn () => [
                'status' => ServiceStatus::ACTIVE,
            ]);
    }
}
