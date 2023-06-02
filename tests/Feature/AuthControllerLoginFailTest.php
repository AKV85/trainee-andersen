<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthControllerLoginFailTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testLogin()
    {
        // Create a user for testing
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);


        // Prepare login data
        $data = [
            'email' => 'test@example.com',
            'password' => 'password12',
        ];

        // Send a POST request to the login endpoint
        $response = $this->json('POST', '/api/auth/login', $data);

        // Assert that the response has a unsuccessful status code
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
