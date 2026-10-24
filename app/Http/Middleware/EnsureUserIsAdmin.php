<?php

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        if (! $user || $user->role !== 'admin') {
            return ApiResponse::error(
                message: 'You are not authorized to access this resource.',
                code: 'ADMIN_ACCESS_REQUIRED',
                status: 403,
            );
        }

        if (! $user->hasVerifiedEmail()) {
            return ApiResponse::error(
                message: 'Please verify your email address first.',
                code: 'EMAIL_NOT_VERIFIED',
                status: 403,
            );
        }

        return $next($request);
    }
}
