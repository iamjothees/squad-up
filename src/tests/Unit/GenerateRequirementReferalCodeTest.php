<?php

use App\Models\Requirement;
use App\Services\RequirementService;

uses(Tests\TestCase::class);
test('it_tests_generate_requirement_referal_code', function () {
    // Arrange
    $service = new RequirementService();
    $requirement = Requirement::make(['id' => 5]);

    // Act
    $code = $service->generateReferalCode(requirement: $requirement);

    // Assert
    expect($code)->toMatch('/REQ-[0-9]{4}-\w{4}/');

});
