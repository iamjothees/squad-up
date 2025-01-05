<?php

use App\DTOs\UserDTO;
use App\Models\User;
use App\Services\UserService;
use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()
    ->extends(Tests\TestCase::class, RefreshDatabase::class)
    ->group('user-service', 'credits-signup-bonus-points');

it('credits signup bonus points', function () {
    // Arrange
    $userService = app(UserService::class);
    $userDTO = UserDTO::fromModel(User::factory()->createQuietly(['referal_partner_code' => 'ABCDEFG']));

    // Act
    $userService->creditSignupBonusPoints(userDTO: $userDTO);
    $user = User::query()->find($userDTO->id);

    // Assert
    expect($user->current_points)->toBeGreaterThan(0);
    expect($user->current_points)->toEqual(app(GeneralSettings::class)->signup_bonus_points);
});
