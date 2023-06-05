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
        $user = User::factory()->create();

        Mail::fake();

        $response = $this->postJson('/api/auth/reset-password', ['email' => $user->email]);

        $response->assertStatus(ResponseAlias::HTTP_OK);

        Mail::assertSent(ResetPasswordEmail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

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
        $response = $this->postJson('/api/auth/reset-password', ['email' => 'nonexistent@example.com']);

        $response->assertStatus(ResponseAlias::HTTP_NOT_FOUND)
            ->assertJson([
                'message' => 'User not found'
            ]);
    }
}
