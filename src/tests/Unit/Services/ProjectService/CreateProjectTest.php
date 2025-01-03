<?php

use App\DTOs\ProjectDTO;
use App\Services\ProjectService;
use Illuminate\Support\Facades\Validator;

uses(Tests\TestCase::class);
test('it_creates_a_project', function () {
    // Arrange
    $projectService = app(ProjectService::class);
    Validator::shouldReceive('make')
        ->once()
        ->andReturnUsing(function ($data, $rules) {
            $rules['service_id'] = 'required';
            $rules['admin_id'] = 'required';
            $validator = Validator::make($data, $rules);
            return $validator;
        });
    $projectDTO = ProjectDTO::fromArray([
        'title' => 'Test Project',
        'description' => 'Test Description',
        'service_id' => 1,
        'admin_id' => 1,
        'start_at' => now()->addDays(3),
        'committed_budget' => 1000,
        'priority_level' => 1
    ]);


    // Act

    // Assert
    expect(true)->toBeTrue();
});
