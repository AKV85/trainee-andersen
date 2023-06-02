<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test the register method of AuthController.
     *
     * @return void
     */
    public function testRegister()
    {
        // Prepare data for the registration request
        $data = [
            'email' => $this->faker->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Send the registration request
        $response = $this->json('POST', '/api/auth/register', $data);

        // Assert that the response has a 201 status code
        $response->assertStatus(201);

        // Assert the JSON structure of the response
        $response->assertJsonStructure(['token']);

        // Assert that the user is created in the database
        $this->assertDatabaseHas('users', ['email' => $data['email']]);
        dump($data);
        Log::info($data);
    }
}
