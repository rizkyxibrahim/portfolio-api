<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ForgotPasswordRequest;
use App\Support\ApiResponse;
use Illuminate\Support\Facades\Password;

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