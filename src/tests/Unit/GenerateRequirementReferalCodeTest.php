<?php

use App\DTOs\RequirementDTO;
use App\Models\Requirement;
use App\Services\RequirementService;

uses(Tests\TestCase::class);
test('it_tests_generate_requirement_referal_code', function () {
    // Arrange
    $service = app(RequirementService::class);
    $requirementDTO = app(RequirementDTO::class);
    $requirementDTO->id = 1;

    // Act
    $code = $service->generateReferalCode(requirementDTO: $requirementDTO);

    // Assert
    expect($code)->toMatch('/REQ-[0-9]{4}-\w{4}/');

});
