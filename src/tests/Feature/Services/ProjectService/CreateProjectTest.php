<?php

use App\DTOs\ProjectDTO;
use App\Enums\ServiceStatus;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use App\Services\ProjectService;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()
    ->extends(Tests\TestCase::class, RefreshDatabase::class)
    ->group('service', 'project-service');

test('it_creates_a_project', function () {
    // Arrange
    $projectService = app(ProjectService::class);

    $projectDTO = ProjectDTO::fromArray([
        'title' => 'Test Project',
        'description' => 'Test Description',
        'service_id' => Service::factory()->active()->create()->id,
        'admin_id' => User::factory()->teamMember()->create()->id,
        'owner_id' => User::factory()->create()->id,
        'start_at' => now()->addDays(3),
        'committed_budget' => 1000,
        'initial_payment' => 500,
        'priority_level' => 1
    ]);

    // Act
    $projectDTO = $projectService->createProject( projectDTO: $projectDTO);

    // Assert
    expect($projectDTO)->toBeInstanceOf(ProjectDTO::class);
    $this->assertModelExists($projectDTO->toModel());
});
