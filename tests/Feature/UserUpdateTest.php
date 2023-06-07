<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserUpdateTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    /**
     * Test user update.
     *
     * @return void
     */
    public function testUpdateUserWithSuccessfulAuth(): void
    {
        $user = User::factory()->create();

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('authToken')->accessToken,
        ])->put(route('users.update', ['user' => $user->id]), $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }

    public function testUpdateUserWithFailedAuth()
    {
        $user = User::factory()->create();

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
        ];

        $unauthorizedUser = User::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $unauthorizedUser->createToken('authToken')->accessToken,
        ])->put(route('users.update', ['user' => $user->id]), $data);

        $response->assertStatus(403);
    }
}
