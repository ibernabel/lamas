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
                'user' => $user,
                'type' => 'Bearer',
                'expires_in' => config('auth.token_expiration_days', 7) * 24 * 60 * 60
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
}

/*
Key improvements and optimizations:

Proper Namespace : Added the correct Api namespace as indicated by the file path.

Form Request Validation : Created a separate LoginRequest class for validation, which helps:

Keeps the controller lean

Improves reusability

Makes validation rules maintainable

Type Hinting : Added return type declarations ( JsonResponse) for better type safety and IDE support.

Error Handling :

Added proper exception handling

Included specific error messages for different scenarios

Added validation error details in the response

Token Configuration :

Made token name configurable via config files

Added token expiration

Included token type and expiration time in response

Response Structure :

Added more information in the success response (token type, expiration)

Standardized error responses

Added HTTP status codes explicitly

These improvements make the code:

More maintainable

More secure

More scalable

Better documented

More robust with proper error handling

More configurable through environment variables

The code maintains the same basic functionality while adding important security and maintainability features.
*/