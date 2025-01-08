<?php

use App\DTOs\PointGenerationDTO;
use App\DTOs\PointRedeemDTO;
use App\DTOs\RequirementDTO;
use App\DTOs\UserDTO;
use App\Enums\Point\GenerationArea;
use App\Models\PointGeneration;
use App\Models\User;
use App\Services\PointService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()
    ->extends(TestCase::class, RefreshDatabase::class)
    ->group('points', 'withdraw-points');
it('withdraws points', function (UserDTO $userDTO, int $points) {
    // Arrange
    $pointGenerationDTO = PointGenerationDTO::fromArray([
        'points' => $points,
        'owner_id' => $userDTO->id,
        'generation_area' => GenerationArea::REFERENCE,
        'credited_at' => now()
    ]);
    PointGeneration::create($pointGenerationDTO->toCreateArray());
    $userDTO->current_points += $points;

    $pointService = app(PointService::class);
    
    // Act
    $pointRedeemDTO = $pointService->requestForRedeem(userDTO: $userDTO, points: $points);

    // Assert
    expect($pointRedeemDTO)->toBeInstanceOf(PointRedeemDTO::class);
})
->with([
    [
        fn () => UserDTO::fromModel( User::factory()->create()->refresh() ),
        10000
    ]
]);
