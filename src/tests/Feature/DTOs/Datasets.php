<?php

use App\DTOs\PointGenerationDTO;
use App\DTOs\PointRedeemDTO;
use App\DTOs\ProjectDTO;
use App\DTOs\ReferenceDTO;
use App\DTOs\RequirementDTO;
use App\DTOs\UserDTO;
use App\Enums\Point\GenerationArea;
use App\Enums\RequirementStatus;
use App\Enums\ServiceStatus;
use App\Models\PointGeneration;
use App\Models\PointRedeem;
use App\Models\Project;
use App\Models\Reference;
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
                'completion_at' => now()->addDays(5),
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
        ],
        [
            UserDTO::class,
            fn () => [
                'name' => 'Test User',
                'email' => 'unique-test-user@test.dev',
                'password' => 'password',
            ]
        ],
        [
            UserDTO::class,
            fn () => [
                'name' => 'Test User',
                'phone' => '9123456789',
                'password' => 'password',
            ]
        ],
        [
            PointGenerationDTO::class,
            fn () => [
                'points' => 100 * 100,
                'generation_area' => GenerationArea::REQUIREMENT,
                'generator_type' => Requirement::class,
                'generator_id' => Requirement::factory()->create()->id,
            ]
        ],
        [
            PointRedeemDTO::class,
            fn () => [
                'owner_id' => User::factory()->create()->id,
                'points' => 100 * 100,
                'redeemed_at' => now()->subDays(3),
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
        [
            ReferenceDTO::class,
            fn () => Reference::factory()->create(),
        ],
        [
            UserDTO::class,
            fn () => User::factory()->create(),
        ],
        [
            PointGenerationDTO::class,
            fn () => PointGeneration::factory()->create(),
        ],
        [
            PointRedeemDTO::class,
            fn () => PointRedeem::factory()->create(),
        ]
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
        [
            UserDTO::class,
            fn () => User::factory()->create(),
            User::class,
        ]
    ]
);