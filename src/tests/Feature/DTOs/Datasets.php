<?php

use App\DTOs\ProjectDTO;
use App\DTOs\ReferenceDTO;
use App\DTOs\RequirementDTO;
use App\Enums\RequirementStatus;
use App\Enums\ServiceStatus;
use App\Models\Project;
use App\Models\Requirement;
use App\Models\Service;
use App\Models\User;
use App\Settings\GeneralSettings;

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
        [
            ReferenceDTO::class,
            fn () => [
                'referenceable_type' => Requirement::class,
                'referenceable_id' => Requirement::factory()->create()->id,
                'referer_id' => User::factory()->create()->id,
                'participation_level' => 1,
                'calc_config' => (new GeneralSettings())->points_config,
            ]
        ],
        [
            ReferenceDTO::class,
            fn () => [
                'referenceable_type' => Requirement::class,
                'referenceable_id' => Requirement::factory()->create()->id,
                'referer_id' => User::factory()->create()->id,
                'participation_level' => 1,
                'calc_config' => (new GeneralSettings())->points_config,
            ]
        ]
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