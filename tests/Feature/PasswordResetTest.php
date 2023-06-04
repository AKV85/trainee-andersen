<?php

use App\Mail\ResetPasswordEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /**
     * Test if the reset password email is sent successfully.
     *
     * @return void
     */
    public function testResetPasswordEmailSentSuccessfully()
    {
// Create a test user
        $user = User::factory()->create();

// Mock the Mail facade
        Mail::fake();

// Make a request to reset the password
        $response = $this->postJson('/api/auth/reset-password', ['email' => $user->email]);

// Assert that the response is successful
        $response->assertStatus(ResponseAlias::HTTP_OK);

// Assert that the reset password email was sent
        Mail::assertSent(ResetPasswordEmail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

// Assert that a reset password record was created in the database
        $this->assertDatabaseHas('reset_password', [
            'user_id' => $user->id,
        ]);
    }
    /**
     * Test if the reset password email is sent successfully.
     *
     * @return void
     */
    public function testResetPasswordUserNotFound()
    {
        // Make a request to reset the password for a non-existent user
        $response = $this->postJson('/api/auth/reset-password', ['email' => 'nonexistent@example.com']);

        // Assert that the response indicates user not found
        $response->assertStatus(ResponseAlias::HTTP_NOT_FOUND)
            ->assertJson([
                'message' => 'User not found'
            ]);
    }
}
