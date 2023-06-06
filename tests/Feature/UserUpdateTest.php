<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Services\UserService;

class UserUpdateTest extends TestCase
{
    use DatabaseTransactions, WithFaker;


    protected $userService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = app(UserService::class);
    }

    public function testUserUpdateSuccess()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'newpassword',
            'password_confirmation'=>'newpassword'
        ];

        $response = $this->actingAs($user)
            ->json('PUT', '/api/users', $data);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User updated successfully',
                'user' => [
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
    }

    public function testUserUpdateError()
    {
        $user = User::factory()->create();

        $data = [
            'email' => 'invalid_email',
        ];

        $response = $this->actingAs($user)
            ->json('PUT', '/api/users', $data);

        $response->assertStatus(422)
            ->assertJson([
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email' => 'invalid_email',
        ]);
    }

}

