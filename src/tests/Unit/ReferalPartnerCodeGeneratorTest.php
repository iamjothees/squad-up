<?php

use App\Models\User;
use App\Services\UserService;

uses(Tests\TestCase::class);

test('it_generates_a_referal_partner_code', function () {
    // Arrange
    $userService = app(UserService::class);
    $user = User::factory()->make(['id' => 21]);

    // Act
    $code = $userService->generateReferalPartnerCode(user: $user);
    echo $code;

    // Assert
    expect($code)->toBeString();
    expect( $code )->toEndWith(str($user->phone)->take(-4));
});
