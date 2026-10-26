<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ForgotPasswordRequest;
use App\Http\Requests\Api\V1\ResetPasswordRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function sendResetLink(ForgotPasswordRequest $request)
    {
        $email = $request->validated('email');

        $status = Password::broker()->sendResetLink(
            ['email' => $email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            return ApiResponse::success(
                data: null,
                message: 'Password reset link has been sent to your email.',
                code: 'PASSWORD_RESET_LINK_SENT',
            );
        }

        return ApiResponse::error(
            title: 'Failed to send reset link.',
            detail: 'Unable to send password reset link at this time.',
            code: 'PASSWORD_RESET_FAILED',
            status: 500,
        );
    }
}

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
