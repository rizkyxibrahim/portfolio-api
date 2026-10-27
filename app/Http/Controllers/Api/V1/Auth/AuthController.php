<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Requests\Api\V1\UploadProfilePhotoRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
        {
            $identity = $request->validated('identity');
            $password = $request->validated('password');

            if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
                $field = 'email';
            } elseif (preg_match('/^\+?[\d\s\-]+$/', $identity)) {
                $field = 'phone';
            } else {
                $field = 'username';
            }

            $user = User::where($field, $identity)->first();

            if (! $user || ! Hash::check($password, $user->password)) {
                return ApiResponse::unauthenticated();
            }

            $user->tokens()->delete();

            $token = $user->createToken('xibra-api-token')->plainTextToken;

            return ApiResponse::success(
                data: [
                    'user' => new UserResource($user),
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
                message: 'Login successful.',
                code: 'LOGIN_SUCCESS',
            );
        }

        public function register(RegisterRequest $request)
        {
            $user = User::create([
                'name' => $request->validated('name'),
                'username' => $request->validated('username'),
                'email' => $request->validated('email'),
                'phone' => $request->validated('phone'),
                'password' => Hash::make($request->validated('password')),
            ]);

            $token = $user->createToken('xibra-api-token')->plainTextToken;

            return ApiResponse::success(
                data: [
                    'user' => new UserResource($user),
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
                message: 'Registration successful.',
                code: 'REGISTER_SUCCESS',
                status: 201,
            );
        }

        public function me(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return ApiResponse::unauthenticated();
        }

        return ApiResponse::success(
            data: new UserResource($user),
            message: 'User profile retrieved.',
            code: 'PROFILE_RETRIEVED',
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return ApiResponse::success(
            message: 'Logout successful.',
            code: 'LOGOUT_SUCCESS',
        );
    }

    public function uploadProfilePhoto(UploadProfilePhotoRequest $request)
    {
        $user = $request->user();

        if (! $user) {
            return ApiResponse::unauthenticated();
        }

        // Delete old photo if exists
        if ($user->profile_photo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
        }

        $path = $request->file('photo')->store('profile_photos', 'public');

        $user->update(['profile_photo_path' => $path]);

        return ApiResponse::success(
            data: [
                'profile_photo_path' => $user->profile_photo_path,
                'profile_photo_url' => $user->profile_photo_url,
            ],
            message: 'Profile photo uploaded.',
            code: 'PROFILE_PHOTO_UPLOADED',
            status: 201,
        );
    }

    public function deleteProfilePhoto(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return ApiResponse::unauthenticated();
        }

        if ($user->profile_photo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
            $user->update(['profile_photo_path' => null]);
        }

        return ApiResponse::success(
            message: 'Profile photo deleted.',
            code: 'PROFILE_PHOTO_DELETED',
        );
    }
}