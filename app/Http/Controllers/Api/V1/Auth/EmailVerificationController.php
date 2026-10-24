<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class EmailVerificationController extends Controller
{
    public function verify(Request $request, string $id, string $hash): JsonResponse
    {
        if (! URL::hasValidSignature($request)) {
            return ApiResponse::error(
                message: 'The verification link is invalid or has expired.',
                code: 'INVALID_VERIFICATION_LINK',
                status: 403,
            );
        }

        $user = User::find($id);

        if (! $user || ! hash_equals(
            sha1($user->getEmailForVerification()),
            $hash
        )) {
            return ApiResponse::error(
                message: 'The verification link is invalid.',
                code: 'INVALID_VERIFICATION_LINK',
                status: 403,
            );
        }

        if ($user->hasVerifiedEmail()) {
            return ApiResponse::success(
                message: 'Email address has already been verified.',
                code: 'EMAIL_ALREADY_VERIFIED',
            );
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return ApiResponse::success(
            message: 'Email address verified successfully.',
            code: 'EMAIL_VERIFIED',
        );
    }

    public function resend(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return ApiResponse::success(
                message: 'Email address has already been verified.',
                code: 'EMAIL_ALREADY_VERIFIED',
            );
        }

        $user->sendEmailVerificationNotification();

        return ApiResponse::success(
            message: 'Verification email sent successfully.',
            code: 'VERIFICATION_EMAIL_SENT',
        );
    }
}
