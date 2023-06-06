<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * Store a new user.
     *
     * @param array $data The user data.
     * @return User The created user.
     */
    public function store(array $data): User
    {
        // Check if email and password are present in the input data
        if (!isset($data['email']) || !isset($data['password'])) {
            throw new \InvalidArgumentException('Email and password are required');
        }
        // Create a new user using the provided data
        return User::create($data);
    }

    public function update(User $user, array $data)
    {
        $user->fill($data);

        if (isset($data['password'])) {
            $user->password = $data['password'];
        }

        if ($user->isDirty()) {
            $user->save();
        }

        return $user;
    }
}
