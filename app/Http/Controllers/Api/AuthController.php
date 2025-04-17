<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle user login and token generation
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            if (!Auth::attempt($request->validated())) {
                return response()->json([
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $user = Auth::user();
            $token = $user->createToken(
                name: config('app.token_name', 'api-token'),
                expiresAt: now()->addDays(config('auth.token_expiration_days', 7))
            )->plainTextToken;

            return response()->json([
                'token' => $token,
                'type' => 'Bearer',
                'expires_in' => config('auth.token_expiration_days', 7) * 24 * 60 * 60,
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'message' => 'Login successful',
                'status_code' => 200,
                'status' => 'success',
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred during authentication'
            ], 500);
        }
    }

    /**
     * Handle user logout and token revocation
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function logout(LoginRequest $request): JsonResponse
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
            'status_code' => 200,
        ], 200);
    }
}
