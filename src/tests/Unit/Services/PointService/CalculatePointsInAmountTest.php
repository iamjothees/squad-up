<?php

use App\Services\PointService;
use App\Settings\PointsSettings;

pest()->extends(Tests\TestCase::class, )->group('point-service', 'calculate-points-in-amount');
test('it_calculates_points_in_amount', function (float $amount, int $participationLevel, float $expectedPointsInAmount) {
    // Arrange
    PointsSettings::fake([
        'points_config' => [
            1 => [
                ['least' => 3500, 'most' => 10000, 'percent' => 3],
                ['least' => 10001, 'most' => 50000, 'percent' => 4],
            ]
        ]
    ]);

    $pointService = app(PointService::class);
    
    // Act
    $pointsInAmount = $pointService->calcPointsInAmount( amount: $amount, participationLevel: $participationLevel );

    // Assert
    expect($pointsInAmount)->toEqual($expectedPointsInAmount);
})
->with([
    [ 0, 1, 0 ],
    [ 1, 1, 0 ],
    [ 3499, 1, 0 ],
    [ 3500, 1, 3500 * 0.03 ],
    [ 3501, 1, 3501 * 0.03 ],
    [ 10000, 1, 10000 * 0.03 ],
    [ 10001, 1, 10001 * 0.04 ],
    [ 50000, 1, 50000 * 0.04 ],
    [ 50001, 1, 50001 * 0.04 ],
]);