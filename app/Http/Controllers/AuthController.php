<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;

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
        $validatedData = $request->validated();

        try {
            // Create a new user
            $user = User::create([
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            // Generate an access token using Laravel Passport
            $token = $user->createToken('authToken')->accessToken;

            // Log successful registration
            Log::info('User registered successfully: ' . $user->email);

            // Return a success response with the token
            return response()->json(['token' => $token], 201);
        } catch (Exception $e) {
            Log::error('Error registering user: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to register user'], 500);
        }
    }
}
