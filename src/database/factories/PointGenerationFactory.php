<?php

namespace Database\Factories;

use App\Enums\Point\GeneratedArea;
use Illuminate\Database\Eloquent\Factories\Factory;

class PointGenerationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'points' => fake()->numberBetween(50, 2500) * 100,
            'generated_area' => fake()->randomElement(GeneratedArea::cases()),
            'generator_type' => fn (array $attributes) => $attributes['generated_area']->generatorKey(),
            'generator_id' => fn (array $attributes) => $attributes['generator_type'] ? $attributes['generator_type']::factory()->create()->id : null,
            'credited_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
