<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

pest()
    ->extends(Tests\TestCase::class, RefreshDatabase::class)
    ->group('enquiries', 'add-by-api');
test('example', function () {

    // Arrange
    $request = Request::create('/', 'POST', [
        'name' => 'John Doe',
        'contact' => '5oLQK@example.com',
        'message' => 'Hello, World!',
    ]);


    // Act
    $response = $this->postJson('/api/enquiry', $request->all());

    // Assert
    $response->assertStatus(200);
});
