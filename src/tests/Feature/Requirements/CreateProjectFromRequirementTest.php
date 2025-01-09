<?php
use App\DTOs\RequirementDTO;
use App\Enums\RequirementStatus;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use App\Services\RequirementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

pest()
    ->extends(Tests\TestCase::class, RefreshDatabase::class)
    ->group('requirements', 'create-project-from-requirement');

test('creates a project from a requirement', function (RequirementDTO $requirementDTO) {
    // Arrange
    $requirementService = app(RequirementService::class);
    Auth::login(User::factory()->create()->assignRole('team-member'));
    $requirementService->createRequirement(requirementDTO: $requirementDTO);

    // Act
    $requirementService->createProject( requirementDTO: $requirementDTO, initialPayment: $requirementDTO->budget / 2 );

    // Assert
    // Requirement
    $requirement = $requirementDTO->toModel();
    expect($requirement->status)->toBe(RequirementStatus::APPROVED);
    expect($requirement->project)->toBeInstanceOf(Project::class);

    // Project
    $project = $requirement->project;
    expect($project->committed_budget)->toBeGreaterThan(0);
    expect($project->initial_payment)->toBeLessThanOrEqual($project->committed_budget);

    // TODO: Reference


    // TODO: Referer

})
->with([
    [
        fn () => RequirementDTO::fromArray([
            'title' => 'Test Requirement',
            'description' => 'Test Description',
            'service_id' => Service::factory()->active()->create()->id,
            'owner_id' => User::factory()->create()->id,
            'referer_id' => User::factory()->create()->id,
            'status' => RequirementStatus::PENDING,
            'budget' => 24999.99
        ])
    ]
]);
