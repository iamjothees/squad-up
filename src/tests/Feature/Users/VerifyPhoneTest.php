<?php

use App\DTOs\UserDTO;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()
    ->extends(Tests\TestCase::class, RefreshDatabase::class)
    ->group('users', 'verify-phone');
it('verifies users phone', function (UserDTO $userDTO) {
    // Arrange
    $userDTOBefore = $userDTO;
    $userService = app(UserService::class);

    // Act
    $userService->verifyPhone(userDTO: $userDTO);
    $user = $userDTO->toModel()->refresh();
    
    // Assert
    expect($userDTOBefore->phone_verified_at)->toBeNull();
    expect($user->phone_verified_at)->not()->toBeNull();
})->with([
    'userDTO' => fn() => UserDTO::fromModel(User::factory()->unverified()->create()),
]);
