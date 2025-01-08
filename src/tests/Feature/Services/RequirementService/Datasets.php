<?php

use App\DTOs\RequirementDTO;
use App\Enums\RequirementStatus;
use App\Models\Service;
use App\Models\User;
use App\PointConfig;

dataset( 'requirements', [
    [ 
        fn () => RequirementDTO::fromArray([
                    'title' => 'Test Requirement',
                    'description' => 'Test Description',
                    'service_id' => Service::factory()->active()->create()->id,
                    'owner_id' => User::factory()->create(['name' => 'Test User 1'])->id,
                    'admin_id' => User::factory()->teamMember()->create(['name' => 'Test User 2'])->id,
                    'referer_id' => User::factory()->create(['name' => 'Test User 3'])->id,
                    'completion_at' => now()->addDays(3),
                    'budget' => 3500,
                    'status' => RequirementStatus::PENDING,
                ])
        , fn () => (int) (3500 * app(PointConfig::class)->getPercent(3500, 1))
    ],
    [ 
        fn () => RequirementDTO::fromArray([
                    'title' => 'Test Requirement',
                    'description' => 'Test Description',
                    'service_id' => Service::factory()->active()->create()->id,
                    'owner_id' => User::factory()->create()->id,
                    'admin_id' => User::factory()->teamMember()->create()->id,
                    'referer_id' => User::factory()->create()->id,
                    'completion_at' => now()->addDays(3),
                    'budget' => 24999.99,
                    'status' => RequirementStatus::PENDING,
                ])
        , fn () => (int) (24999.99 * app(PointConfig::class)->getPercent(24999.99, 1))
    ],
    [ 
        fn () => RequirementDTO::fromArray([
                    'title' => 'Test Requirement',
                    'description' => 'Test Description',
                    'service_id' => Service::factory()->active()->create()->id,
                    'owner_id' => User::factory()->create()->id,
                    'admin_id' => User::factory()->teamMember()->create()->id,
                    'referer_id' => User::factory()->create()->id,
                    'completion_at' => now()->addDays(3),
                    'budget' => 249999.99,
                    'status' => RequirementStatus::PENDING,
                ])
        , fn () => (int) (249999.99 * app(PointConfig::class)->getPercent(249999.99, 1))
    ],
    [ 
        fn () => RequirementDTO::fromArray([
                    'title' => 'Test Requirement',
                    'description' => 'Test Description',
                    'service_id' => Service::factory()->active()->create()->id,
                    'owner_id' => User::factory()->create()->id,
                    'admin_id' => User::factory()->teamMember()->create()->id,
                    'completion_at' => now()->addDays(3),
                    'budget' => 7500,
                    'status' => RequirementStatus::PENDING,
                ])
        , fn () => (int) (7500 * app(PointConfig::class)->getPercent(7500, 1))
    ]
] );