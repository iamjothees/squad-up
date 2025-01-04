<?php

use App\DTOs\ProjectDTO;
use App\DTOs\RequirementDTO;
use App\Enums\RequirementStatus;
use App\Enums\ServiceStatus;
use App\Models\Project;
use App\Models\Requirement;
use App\Models\Service;
use App\Models\User;

dataset(
    'dtosForFromArray',
    [
        [
            ProjectDTO::class, 
            fn () => [
                'title' => 'Test Project',
                'description' => 'Test Description',
                'service_id' => Service::factory()->create(['status' => ServiceStatus::ACTIVE])->id,
                'admin_id' => User::factory()->teamMember()->create()->id,
                'start_at' => now()->addDays(3),
                'committed_budget' => 24999.99,
                'initial_payment' => 12000.00,
                'priority_level' => 1
            ],
        ],
        [
            RequirementDTO::class, 
            fn () => [
                'title' => 'Test Requirement',
                'description' => 'Test Description',
                'service_id' => Service::factory()->create(['status' => ServiceStatus::ACTIVE])->id,
                'owner_id' => User::factory()->create()->id,
                'admin_id' => User::factory()->teamMember()->create()->id,
                'completion_at' => now()->addDays(3),
                'budget' => 24999.99,
                'status' => RequirementStatus::PENDING,
            ],
        ],
    ]
);


dataset(
    'dtosForFromModel',
    [
        [
            ProjectDTO::class,
            fn () => Project::factory()->create(),
        ],
        [
            RequirementDTO::class, 
            fn () => Requirement::factory()->create(),
        ],
    ]
);

dataset(
    'dtosForToConversionFromModelData',
    [
        [
            ProjectDTO::class,
            fn () => Project::factory()->create(),
            Project::class,
        ],
        [
            RequirementDTO::class, 
            fn () => Requirement::factory()->create(),
            Requirement::class,
        ],
    ]
);