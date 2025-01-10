<?php

namespace Database\Factories;

use App\Enums\Point\GenerationArea;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\Relation;

class PointGenerationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'points' => fake()->numberBetween(50, 2500) * 100,
            'owner_id' => User::factory()->create()->id,
            'generation_area' => fake()->randomElement(GenerationArea::cases()),
            'generator_type' => fn (array $attributes) => $attributes['generation_area']->generatorKey(),
            'generator_id' => fn (array $attributes) => $attributes['generator_type'] ? Relation::getMorphedModel($attributes['generator_type'])::factory()->create()->id : null,
            'credited_at' => fake()->boolean() ? fake()->dateTimeBetween('-1 year', 'now') : null,
        ];
    }
}
