<?php

use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()
    ->extends(Tests\TestCase::class, RefreshDatabase::class)
    ->group('user-service', 'credits-signup-bonus-points');

it('credits signup bonus points', function () {
    // Arrange & Act
    $user = User::factory()->create();
    $user->refresh();

    // Assert
    expect($user->current_points)->toBeGreaterThan(0);
    expect($user->current_points)->toEqual(app(GeneralSettings::class)->signup_bonus_points);
});
