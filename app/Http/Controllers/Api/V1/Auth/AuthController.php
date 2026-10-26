<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identity' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors()->toArray());
        }

        $identity = $request->input('identity');
        $password = $request->input('password');

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
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'phone' => $user->phone,
                    'email' => $user->email,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ],
            message: 'Login successful.',
            code: 'LOGIN_SUCCESS',
        );
    }

    public function me(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return ApiResponse::unauthenticated();
        }

        return ApiResponse::success(
            data: [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'phone' => $user->phone,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at?->toISOString(),
            ],
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
}
