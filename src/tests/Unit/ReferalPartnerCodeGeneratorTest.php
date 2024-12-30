<?php

use App\Models\User;
use App\Service\UserService;

uses(Tests\TestCase::class);

test('it_generates_a_referal_partner_code', function () {
    // Arrange
    $userService = new UserService();
    $user = User::factory()->make(['id' => 21]);

    // Act
    $code = $userService->generateReferalPartnerCode(user: $user);
    echo $code;

    // Assert
    expect($code)->toBeString();
    expect( $code )->toEndWith(str($user->phone)->take(-4));
});
