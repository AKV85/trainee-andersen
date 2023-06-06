<?php

namespace Tests\Feature;

use App\Mail\ResetPasswordEmail;
use App\Models\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use DatabaseTransactions;
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

    public function testResetPasswordWithExpiredToken()
    {
        $user = User::factory()->create();

        $expiredToken = DB::table('reset_password')->insertGetId([
            'user_id' => $user->id,
            'token' => 'expired_token',
            'created_at' => Carbon::now()->subDay(), // Просроченная дата создания токена
        ]);

        $response = $this->postJson('/api/auth/update-password', [
            'token' => 'expired_token',
            'password' => 'newpassword',
        ]);

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertEquals('Invalid token, please reset password again', $response->json('message'));
    }

    public function testUpdatePassword()
    {
        // Создаем пользователя
        $user = User::factory()->create();

        // Создаем запись о сбросе пароля для пользователя
        $resetPassword = ResetPassword::factory()->create([
            'user_id' => $user->id,
        ]);

        // Генерируем новый пароль
        $newPassword = 'new_password';

        // Отправляем запрос на обновление пароля
        $response = $this->postJson('/api/auth/update-password', [
            'token' => $resetPassword->token,
            'password' => $newPassword,
        ]);

        // Проверяем, что ответ имеет статус 200 OK
        $response->assertStatus(ResponseAlias::HTTP_OK);

        // Проверяем, что пароль пользователя был успешно обновлен в базе данных
        $this->assertTrue(Hash::check($newPassword, $user->fresh()->password));

        // Проверяем, что запись о сбросе пароля была удалена из базы данных
        $this->assertDatabaseMissing('reset_password', [
            'user_id' => $user->id,
        ]);
    }
}
