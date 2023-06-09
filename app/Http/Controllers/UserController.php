<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\UserShowRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    protected UserService $userService;
    protected $pdf;

    public function __construct(UserService $userService, PDF $pdf)
    {
        $this->userService = $userService;
        $this->pdf = $pdf;
    }

    public function index(UserIndexRequest $request, User $user): JsonResponse
    {
        $users = User::all();
        $emails = $users->pluck('email')->toArray();

        return response()->json([
            'users' => $emails
        ]);
    }

    public function show(UserShowRequest $request, User $user)
    {
        return response()->json(new UserResource($user));
    }

    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $loggedUser = $request->user();

        $updatedUser = $this->userService->update($loggedUser, $request->validated());

        return response()->json($updatedUser);
    }

    public function destroy(DeleteUserRequest $request, User $user)
    {
        $request->authorize();
        $this->userService->destroy($user);

        return response()->json(['message' => 'Account deleted successfully']);
    }
}

