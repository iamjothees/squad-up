<?php

use App\DTOs\RequirementDTO;
use App\Models\Requirement;
use App\Services\RequirementService;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()
    ->extends(Tests\TestCase::class, RefreshDatabase::class)
    ->group('service', 'requirement-service', 'create-requirement');
it('creates a new requirement', function (RequirementDTO $requirementDTO, int $expectedPoints) {
    // Arrange
    $requirementService = app(RequirementService::class);

    // Act
    $requirementDTO = $requirementService->createRequirement( requirementDTO: $requirementDTO);

    // Assert
    expect($requirementDTO)->toBeInstanceOf(RequirementDTO::class);
    
    expect(
        Requirement::find($requirementDTO->id)->attributesToArray()
    )->toEqual(
        Requirement::latest()->first()->attributesToArray()
    );
    if ($requirementDTO->toModel()->reference){
        expect(
            $requirementDTO->toModel()->reference->referer->nonCreditedPoints()
        )
        ->toEqual( $expectedPoints );
    }
})
->with('requirements');
