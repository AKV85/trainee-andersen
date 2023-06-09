<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Mail\ResetPasswordEmail;
use App\Models\ResetPassword;
use App\Models\User;
use App\Services\PasswordService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    protected UserService $userService;
    protected PasswordService $passwordService;

    /**
     * AuthController constructor.
     *
     * @param UserService $userService The user service instance.
     * @param PasswordService $passwordService The password service instance.
     */
    public function __construct(UserService $userService, PasswordService $passwordService)
    {
        $this->userService = $userService;
        $this->passwordService = $passwordService;
    }

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request The registration request.
     * @return JsonResponse The JSON response.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->store($request->validated());

            $token = $user->createToken('authToken')->accessToken;

            Log::info('User registered successfully: ' . $user->email);

            return response()->json(['authToken' => $token], Response::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error('Error registering user: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to register user'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Log in the user.
     *
     * @param LoginRequest $request The login request.
     * @return JsonResponse The JSON response.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $user = User::where('email', $credentials['email'])->first();

        if ($user && password_verify($credentials['password'], $user->password)) {
            $token = $user->createToken('authToken')->accessToken;
            Log::info('User logged in successfully: ' . $request->email);
            return response()->json(['authToken' => $token], Response::HTTP_OK);
        }
        return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Reset user password.
     *
     * @param ResetPasswordRequest $request The reset password request.
     * @return JsonResponse The JSON response.
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $resetPassword = $this->passwordService->createResetPassword($user);

            Mail::to($user->email)->send(new ResetPasswordEmail($resetPassword->token));

            return response()->json([
                'message' => 'Reset password email sent'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error sending reset password email: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send reset password email'],
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Update user password.
     *
     * @param UpdatePasswordRequest $request The update password request.
     * @return JsonResponse The JSON response.
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {

        $resetPassword = ResetPassword::where('token', $request->token)->first();

        if (!$resetPassword || !$this->passwordService->isTokenValid($resetPassword)) {
            return response()->json(['message' => 'Invalid token, please reset password again'],
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $user = $resetPassword->user;
            $user->password = $request->password;
            $user->save();
            $resetPassword->delete();

            return response()->json(['message' => 'Password updated'], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error updating password: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update password'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
