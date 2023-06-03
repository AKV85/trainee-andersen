<?php

namespace App\Services;

use App\Models\ResetPassword;
use App\Models\User;
use Illuminate\Support\Str;

class PasswordService
{
    public function createResetPassword(User $user): ResetPassword
    {
        $token = $this->generateToken();

        $resetPassword = ResetPassword::create([
            'user_id' => $user->id,
            'token' => $token,
        ]);

        return $resetPassword;
    }

    public function isTokenValid(ResetPassword $resetPassword): bool
    {
        return $resetPassword->created_at->gt(now()->subHours(2));
    }

    public function generateToken(): string
    {
        return Str::random(60);
    }

}
