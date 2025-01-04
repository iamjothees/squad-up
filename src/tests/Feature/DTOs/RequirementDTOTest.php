<?php

use App\DTOs\RequirementDTO;
use App\Enums\RequirementStatus;
use App\Enums\ServiceStatus;
use App\Models\Requirement;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->extend(Tests\TestCase::class, RefreshDatabase::class)->group('dto', 'requirement-dto');
describe('it_tests_requirement_dto', function(){

    test('it_tests_from_array', function () {
        // Arrange
        $this->seed(RolesPermissionsSeeder::class);

        // Act
        $requirementDTO = RequirementDTO::fromArray([
            'title' => 'Test Requirement',
            'description' => 'Test Description',
            'service_id' => Service::factory()->create(['status' => ServiceStatus::ACTIVE])->id,
            'owner_id' => User::factory()->create()->id,
            'admin_id' => User::factory()->teamMember()->create()->id,
            'completion_at' => now()->addDays(3),
            'budget' => 24999.99,
            'status' => RequirementStatus::PENDING,
        ]);
        
        // Assert
        expect($requirementDTO)->toBeInstanceOf(RequirementDTO::class);
    })->group('from-array');

    test('it_tests_from_model', function () {
        // Arrange
        $this->seed(RolesPermissionsSeeder::class);
        
        // Act
        $requirementDTO = RequirementDTO::fromModel(Requirement::factory()->create());
        
        // Assert
        expect($requirementDTO)->toBeInstanceOf(RequirementDTO::class);
    })->group('from-model');

    test('it_tests_to_model', function () {
        // Arrange
        $this->seed(RolesPermissionsSeeder::class);
        $requirementDTO = RequirementDTO::fromModel(Requirement::factory()->create());

        // Act
        $requirement = $requirementDTO->toModel();

        // Assert
        expect($requirement)->toBeInstanceOf(Requirement::class);
    });

    test('it_tests_to_array', function () {
        // Arrange
        $this->seed(RolesPermissionsSeeder::class);
        $requirementDTO = RequirementDTO::fromModel(Requirement::factory()->create());

        // Act
        $array = $requirementDTO->toArray();

        // Assert
        expect($array)->toBeArray();
    });

    test('it_tests_to_create_array', function () {
        // Arrange
        $this->seed(RolesPermissionsSeeder::class);
        $requirementDTO = RequirementDTO::fromModel(Requirement::factory()->create());

        // Act
        $array = $requirementDTO->toCreateArray();

        // Assert
        expect($array)->toBeArray();
        expect($array)->not()->toHaveKeys(['id']);
    });
});
