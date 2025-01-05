<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Requirement;
use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reference>
 */
class ReferenceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'referenceable_type' => fake()->randomElement([Project::class, Requirement::class]),
            'referenceable_id' => fn (array $attributes) => $attributes['referenceable_type']::factory()->create()->id,
            'referer_id' => User::factory()->create(),
            'participation_level' => fake()->numberBetween(1, 10),
            'calc_config' => (new GeneralSettings())->points_config
        ];
    }
}
