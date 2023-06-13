<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }
    public function update(User $user, User $model)
    {
        return $user->id === $model->id;
    }

    public function show(User $user, User $model)
    {
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model)
    {
        return $user->id === $model->id;
    }

}
