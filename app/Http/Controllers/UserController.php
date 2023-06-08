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

    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $loggedUser = $request->user();

        $updatedUser = $this->userService->update($loggedUser, $request->validated());

        return response()->json($updatedUser);
    }
}
