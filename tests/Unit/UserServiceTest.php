<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the store method of UserService.
     *
     * @return void
     */
    public function testStore()
    {
        // Create an instance of UserService through the Service Container
        $userService = app()->make(UserService::class);

        // Prepare data for the new user
        $data = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        // Call the store method
        $createdUser = $userService->store($data);

        // Assert that the createdUser is an instance of User model
        $this->assertInstanceOf(User::class, $createdUser);

        // Assert that the createdUser has the correct email
        $this->assertEquals('test@example.com', $createdUser->email);
    }
}
