<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthControllerLoginTest extends TestCase
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
            'password' => 'password123',
        ];

        // Send a POST request to the login endpoint
        $response = $this->json('POST', '/api/auth/login', $data);

        // Assert that the response has a successful status code
        $response->assertStatus(Response::HTTP_OK);

        // Assert that the response contains an access token
        $response->assertJsonStructure([
            'token',
        ]);
    }
}
