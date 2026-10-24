
<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\EmailVerificationController;
use App\Http\Controllers\Api\V1\Public\HealthController;
use App\Http\Controllers\Api\V1\Admin\DashboardController;
use App\Http\Controllers\Api\V1\Auth\LocaleController as AuthLocaleController;
use App\Http\Controllers\Api\V1\Public\LocaleController as PublicLocaleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    // Public Routes
    Route::prefix('public')->group(function (): void {
        Route::get('/health', HealthController::class);
        Route::get('/locales', PublicLocaleController::class)->name('public.locales');
    });

    // Admin Routes
    Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function (): void {
        Route::get('/dashboard', DashboardController::class)->name('admin.dashboard');
    });

    // Auth Routes
    Route::prefix('auth')->group(function (): void {
        Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('auth.register');
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('auth.login');
        Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify',])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

        Route::middleware('auth:sanctum')->group(function (): void {
            Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
            Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
            Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend',])->middleware('throttle:6,1')->name('verification.send');
            Route::put('/locale', [AuthLocaleController::class, 'update'])->name('auth.locale.update');
        });
    });
});
