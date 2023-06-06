<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthControllerLoginFailTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    public function testLogin()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);


        $data = [
            'email' => $user->email,
            'password' => 'passwordWrong',
        ];

        $response = $this->json('POST', '/api/auth/login', $data);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
