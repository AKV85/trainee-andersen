<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function index(): JsonResponse
    {
        $users = User::all();
        $emails = $users->pluck('email')->toArray();

        return response()->json([
            'users' => $emails
        ]);
    }

    public function show(User $user)
    {
        $this->authorize('show', $user);

        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $loggedUser = $request->user();

        $updatedUser = $this->userService->update($loggedUser, $request->validated());

        return response()->json($updatedUser);
    }
}
