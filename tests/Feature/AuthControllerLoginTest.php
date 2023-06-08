<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerLoginTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    public function testLogin()
    {
        $plainPassword = 'password123';
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make($plainPassword),
        ]);


        $data = [
            'email' => $user->email,
            'password' => $plainPassword,
        ];

        $response = $this->json('POST', '/api/auth/login', $data);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'authToken',
        ]);
    }
}
