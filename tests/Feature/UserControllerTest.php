<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    public function testShow()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('users.show', $user));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }


    public function testIndex()
    {
        $users = User::factory()->count(5)->create();

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson('/api/users');

        $response->assertStatus(200);
    }

    public function testShowUnauthorized()
    {
        $user = User::factory()->create();

        $response = $this->getJson(route('users.show', $user));

        $response->assertStatus(401);
    }

    public function testIndexUnauthorized()
    {
        $users = User::factory()->count(5)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(401);
    }
}
