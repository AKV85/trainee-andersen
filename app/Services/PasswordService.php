<?php

namespace App\Services;

use App\Models\ResetPassword;
use App\Models\User;
use Illuminate\Support\Str;

class PasswordService
{
    /**
     * Create a new reset password record for the user.
     *
     * @param User $user The user for whom to create the reset password record.
     * @return ResetPassword The created reset password record.
     */
    public function createResetPassword(User $user): ResetPassword
    {
        $token = $this->generateToken();

        $resetPassword = ResetPassword::create([
            'user_id' => $user->id,
            'token' => $token,
        ]);

        return $resetPassword;
    }
    /**
     * Check if the reset password token is still valid.
     *
     * @param ResetPassword $resetPassword The reset password record to check.
     * @return bool True if the token is valid, false otherwise.
     */
    public function isTokenValid(ResetPassword $resetPassword): bool
    {
        return $resetPassword->created_at->gt(now()->subHours(2));
    }
    /**
     * Generate a random token for the reset password record.
     *
     * @return string The generated token.
     */
    public function generateToken(): string
    {
        return Str::random(60);
    }

}
