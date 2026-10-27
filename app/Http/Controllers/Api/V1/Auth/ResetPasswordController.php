<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ResetPasswordRequest;
use App\Support\ApiResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function reset(ResetPasswordRequest $request)
    {
        $validated = $request->validated();

        $status = Password::broker()->reset(
            [
                'email' => $validated['email'],
                'token' => $validated['token'],
                'password' => $validated['password'],
                'password_confirmation' => $validated['password_confirmation'],
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                // Revoke all active tokens (logout from all devices)
                $user->tokens()->delete();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return ApiResponse::success(
                data: null,
                message: 'Password has been changed successfully. Please log in with your new password.',
                code: 'PASSWORD_RESET_SUCCESS',
            );
        }

        return ApiResponse::error(
            title: 'Password reset failed.',
            detail: $status,
            code: 'PASSWORD_RESET_INVALID_TOKEN',
            status: 422,
        );
    }
}