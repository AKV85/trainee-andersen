<?php

namespace Database\Factories;

use App\Models\ResetPassword;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResetPasswordFactory extends Factory
{
    protected $model = ResetPassword::class;

    public function definition()
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'token' => $this->faker->uuid,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

