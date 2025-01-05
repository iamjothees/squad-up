<?php

use App\PointConfig;
use App\Services\PointService;
use Mockery\MockInterface;

pest()->extend(Tests\TestCase::class)->group('point-config');

test('it_gets_percent_for_point_calculations', 
    function (float $amount, int $participationLevel, float $expectedPercent) {
        // Arrange
        $config = [
            1 => [
                ['least' => 3500, 'most' => 10000, 'percent' => 3],
                ['least' => 10001, 'most' => 50000, 'percent' => 4],
            ],
        ];
        $pointConfig = new PointConfig( $config );

        // Act
        
        $percent = $pointConfig->getPercent(amount: $amount, participationLevel: $participationLevel);

        // Assert
        expect( $percent )->toEqual($expectedPercent);
    }
)
->with([
    [ 0, 1, 0 ],
    [ 1, 1, 0 ],
    [ 3499, 1, 0 ],
    [ 3500, 1, 3 ],
    [ 3501, 1, 3 ],
    [ 10000, 1, 3 ],
    [ 10001, 1, 4 ],
    [ 50000, 1, 4 ],
    [ 50001, 1, 4 ],
]);