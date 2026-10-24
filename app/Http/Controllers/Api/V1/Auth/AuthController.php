<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => Str::lower($validated['email']),
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
            'role' => 'user',
            'locale' => $validated['locale'] ?? 'en',
        ]);

        $user->sendEmailVerificationNotification();

        return ApiResponse::success(
            data: [
                'user' => $user->only([
                    'id',
                    'name',
                    'username',
                    'email',
                    'phone',
                    'role',
                    'locale',
                    'email_verified_at',
                ]),
            ],
            message: 'Registration successful. Please verify your email address.',
            code: 'REGISTRATION_SUCCESSFUL',
            status: 201,
        );
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $identifier = trim($validated['identifier']);

        $user = User::query()
            ->where('username', Str::lower($identifier))
            ->orWhere('email', Str::lower($identifier))
            ->orWhere('phone', $identifier)
            ->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return ApiResponse::error(
                message: 'The provided credentials are incorrect.',
                code: 'INVALID_CREDENTIALS',
                status: 401,
            );
        }

        $token = $user->createToken(
            $validated['device_name'] ?? 'portfolio-web'
        )->plainTextToken;

        return ApiResponse::success(
            data: [
                'user' => $user->only([
                    'id',
                    'name',
                    'username',
                    'email',
                    'phone',
                    'role',
                    'locale',
                    'email_verified_at',
                ]),
                'token' => $token,
                'token_type' => 'Bearer',
                'email_verified' => $user->hasVerifiedEmail(),
            ],
            message: 'Login successful.',
            code: 'LOGIN_SUCCESSFUL',
        );
    }

    public function me(Request $request): JsonResponse
    {
        return ApiResponse::success(
            data: [
                'user' => $request->user()->only([
                    'id',
                    'name',
                    'username',
                    'email',
                    'phone',
                    'role',
                    'locale',
                    'email_verified_at',
                ]),
                'email_verified' => $request->user()->hasVerifiedEmail(),
            ],
            message: 'Authenticated user retrieved successfully.',
            code: 'AUTH_USER_RETRIEVED',
        );
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return ApiResponse::success(
            message: 'Logout successful.',
            code: 'LOGOUT_SUCCESSFUL',
        );
    }
}
