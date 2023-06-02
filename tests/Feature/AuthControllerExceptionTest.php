<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerExceptionTest extends TestCase
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
            'password' => 'password1',
            'password_confirmation' => 'password22',
        ];

        // Send the registration request
        $response = $this->json('POST', '/api/auth/register', $data);

        // Assert that the response has a 422 status code
        $response->assertStatus(422);
    }
}
