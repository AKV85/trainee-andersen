<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUsers(): JsonResponse
    {
        $users = User::all();

        return response()->json([
            'users' => $users,
            'message' => 'Users found',
            'status' => 1
        ]);
    }


    public function update(UserUpdateRequest $request)
    {
        $user = auth()->user();
        $this->authorize('update', $user);

        $data = $request->only(['name', 'email']);

        $user = $this->userService->update($user, $data);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }


}
