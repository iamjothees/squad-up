<?php

use App\DTOs\ProjectDTO;
use App\Enums\ServiceStatus;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->extend(Tests\TestCase::class, RefreshDatabase::class)->group('dto', 'model-dto-abstraction');

describe('it_tests_model_dto_abstraction', function(){

    test('it_tests_from_array', function ($dtoType, array $array) {
        // Arrange
        $this->seed(RolesPermissionsSeeder::class);

        // Act
        $dto = app($dtoType)::fromArray($array);
        
        // Assert
        expect($dto)->toBeInstanceOf($dtoType);
    })
    ->with([
        [
            'dtoType' => ProjectDTO::class, 
            'array' => [
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
    ])->group('model-dto-abstraction-from-array');

    test('it_tests_from_model', function () {
        // Arrange
        $this->seed(RolesPermissionsSeeder::class);
        
        // Act
        $projectDTO = ProjectDTO::fromModel(Project::factory()->create());
        
        // Assert
        expect($projectDTO)->toBeInstanceOf(ProjectDTO::class);
    });

    test('it_tests_to_model', function () {
        // Arrange
        $this->seed(RolesPermissionsSeeder::class);
        $projectDTO = ProjectDTO::fromModel(Project::factory()->create());

        // Act
        $project = $projectDTO->toModel();

        // Assert
        expect($project)->toBeInstanceOf(Project::class);
    });

    test('it_tests_to_array', function () {
        // Arrange
        $this->seed(RolesPermissionsSeeder::class);
        $projectDTO = ProjectDTO::fromModel(Project::factory()->create());

        // Act
        $array = $projectDTO->toArray();

        // Assert
        expect($array)->toBeArray();
    });

    test('it_tests_to_create_array', function () {
        // Arrange
        $this->seed(RolesPermissionsSeeder::class);
        $projectDTO = ProjectDTO::fromModel(Project::factory()->create());

        // Act
        $array = $projectDTO->toCreateArray();

        // Assert
        expect($array)->toBeArray();
        expect($array)->not()->toHaveKeys(['id']);
    });
});
