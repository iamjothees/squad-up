<?php

namespace Database\Factories;

use App\Enums\RequirementStatus;
use App\Models\Project;
use App\Models\Requirement;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Requirement>
 */
class RequirementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'referal_code' => fake()->unique()->numerify('RQ-########'),
            'title' => fake()->sentence(),
            'description' => fake()->text(100),
            'service_id' => Service::factory()->create()->id,
            'owner_id' => User::factory()->create()->id,
            'admin_id' => User::factory()->teamMember()->create()->id,
            'completion_at' => fake()->date(),
            'budget' => fake()->numberBetween(1000, 10000),
            'status' => RequirementStatus::PENDING,
            'project_id' => null,
        ];
    }

    public function approved(): self
    {
        return $this->state([
            'status' => RequirementStatus::APPROVED,
            'project_id' => Project::factory()->create()->id,
        ]);
    }

    public function rejected(): self
    {
        return $this->state([
            'status' => RequirementStatus::REJECTED,
            'project_id' => null,
        ]);
    }

    public function inProgress(): self
    {
        return $this->state([
            'status' => RequirementStatus::IN_PROGRESS,
            'project_id' => null,
        ]);
    }
}
