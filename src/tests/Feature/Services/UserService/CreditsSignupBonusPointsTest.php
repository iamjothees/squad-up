<?php

use App\DTOs\UserDTO;
use App\Models\User;
use App\Services\UserService;
use App\Settings\PointsSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()
    ->extends(Tests\TestCase::class, RefreshDatabase::class)
    ->group('user-service', 'credits-signup-bonus-points');

it('credits signup bonus points', function () {
    // Arrange & Act
    User::unsetEventDispatcher();
    $user = User::factory()->create();
    $user->refresh();

    // Act
    app(UserService::class)->creditSignupBonusPoints(userDTO: UserDTO::fromModel($user));
    $user->refresh();

    // Assert
    expect($user->current_points)->toBeGreaterThan(0);
    expect($user->current_points)->toEqual(app(PointsSettings::class)->signup_bonus_points);
});

it('credits signup bonus points only one time', function () {
    // Arrange
    User::unsetEventDispatcher();
    $user = User::factory()->create();
    $user->refresh();

    // Act
    app(UserService::class)->creditSignupBonusPoints(userDTO: UserDTO::fromModel($user));
    app(UserService::class)->creditSignupBonusPoints(userDTO: UserDTO::fromModel($user));

})->throws(Exception::class, 'User already has signup bonus');