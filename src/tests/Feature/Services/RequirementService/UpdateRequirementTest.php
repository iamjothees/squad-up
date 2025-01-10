<?php

use App\DTOs\RequirementDTO;
use App\Enums\RequirementStatus;
use App\Models\Requirement;
use App\Models\Service;
use App\Models\User;
use App\PointConfig;
use App\Services\RequirementService;
use App\Settings\PointsSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()
    ->extends(Tests\TestCase::class, RefreshDatabase::class)
    ->group('service', 'requirement-service', 'update-requirement');
it('updates a requirement', function (RequirementDTO $requirementDTO, int $expectedPoints) {
    // Arrange
    $requirementService = app(RequirementService::class);
    $requirementDTO = $requirementService->createRequirement( requirementDTO: $requirementDTO );
    $reference = $requirementDTO->toModel()->reference;

    // Act

    $editedRequirementDTO = RequirementDTO::fromArray([
        ...$requirementDTO->toArray(),
        'title' => "ABC",
        'description' => "DEF",
        'referer_id' => User::factory()->create(['name' => 'Test User 4'])->id,
        'budget' => 9999.99,
    ]);
    $expectedPointsAfterEdit = (int) (9999.99 * app(PointConfig::class)->getPercent(9999.99, 1));

    $updatedRequirementDTO = $requirementService->updateRequirement( requirementDTO: $editedRequirementDTO );

    // Assert
    expect($updatedRequirementDTO)->toBeInstanceOf(RequirementDTO::class);
    
    expect(
        Requirement::find($updatedRequirementDTO->id)->attributesToArray()
    )->toEqual(
        Requirement::latest()->first()->attributesToArray()
    );

    if ($reference){
        expect( $reference->referer->nonCreditedPoints() )->toEqual( 0 );
    }

    if ($updatedRequirementDTO->toModel()->reference){
        expect(
            $updatedRequirementDTO->toModel()->reference->referer->nonCreditedPoints()
        )
        ->toEqual( $expectedPointsAfterEdit );
    }
})
->with('requirements');
