<?php

use App\DTOs\PointGenerationDTO;
use App\Enums\Point\GenerationArea;
use App\Models\PointGeneration;
use App\Models\User;
use App\Services\PointService;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()
    ->extends(Tests\TestCase::class, RefreshDatabase::class)
    ->group('points', 'destroy-points');

it('destroys points', function () {
    // Arrange
    $pointService = app(PointService::class);
    $user = User::factory()->create();
    $pointGeneration = PointGeneration::factory()->create([
        'points' => 5000,
        'owner_id' => $user->id,
        'generation_area' => GenerationArea::SIGNUP,
        'credited_at' => null,
    ]);
    $pointGenerationDTO = PointGenerationDTO::fromModel($pointGeneration);
    
    // Act
    $pointService->destroy( pointGenerationDTO: $pointGenerationDTO );
    
    // Assert
    expect($pointGeneration->trashed())->toBe(true);
});
