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

        // Always return success for security (don't reveal if email exists)
        // The Password broker will only send email if user exists
        Password::broker()->sendResetLink(['email' => $email]);

        return ApiResponse::success(
            data: null,
            message: 'If the email exists, a password reset link has been sent.',
            code: 'PASSWORD_RESET_LINK_SENT',
        );
    }
}