<?php

use App\DTOs\RequirementDTO;
use App\Enums\RequirementStatus;
use App\Models\Service;
use App\Models\User;
use App\Services\RequirementService;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()
    ->extends(Tests\TestCase::class, RefreshDatabase::class)
    ->group('requirements', 'accept-requirement');

test('accepts a requirement', function (RequirementDTO $requirementDTO) {
    // Arrange
    $requirementService = app(RequirementService::class);
    $requirementService->createRequirement(requirementDTO: $requirementDTO);

    // Act
    $requirementService->accept( requirementDTO: $requirementDTO );

    // Assert
    // Requirement
    $requirement = $requirementDTO->toModel();
    expect($requirement->status)->toBe(RequirementStatus::IN_PROGRESS);
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
