<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param RegisterRequest $request The registration request.
     * @return JsonResponse The JSON response.
     */
    public function register(RegisterRequest $request): JsonResponse
    {

        try {
            // Create a new user
            $user = User::create($request->validated());

            // Generate an access token using Laravel Passport
            $token = $user->createToken('authToken')->accessToken;

            // Log successful registration
            Log::info('User registered successfully: ' . $user->email);

            // Return a success response with the token and HTTP status code 201 (HTTP_CREATED)
            return response()->json(['token' => $token], Response::HTTP_CREATED);
        } catch (Exception $e) {
            Log::error('Error registering user: ' . $e->getMessage());
            // Return an error response with the message 'Failed to register user' and HTTP status code 422 (HTTP_UNPROCESSABLE_ENTITY)
            return response()->json(['error' => 'Failed to register user'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
