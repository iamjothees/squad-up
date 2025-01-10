<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Requirement;
use App\Models\User;
use App\Settings\PointsSettings;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reference>
 */
class ReferenceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'referenceable_type' => app(fake()->randomElement([Project::class, Requirement::class]))->getMorphClass(),
            'referenceable_id' => fn (array $attributes) => Relation::getMorphedModel($attributes['referenceable_type'])::factory()->create()->id,
            'referer_id' => User::factory()->create(),
            'participation_level' => fake()->numberBetween(1, 10),
        ];
    }
}
