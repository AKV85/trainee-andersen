<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerExceptionTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /**
     * Test the register method of AuthController.
     *
     * @return void
     */
    public function testRegister()
    {
        $data = [
            'email' => $this->faker->safeEmail,
            'password' => 'password1',
            'password_confirmation' => 'password22',
        ];

        $response = $this->json('POST', '/api/auth/register', $data);

        $response->assertStatus(422);
    }
}
