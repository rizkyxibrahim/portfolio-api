<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use App\Http\Controllers\Api\V1\ScheduleController;
use App\Http\Controllers\Api\V1\TodoController;
use App\Http\Controllers\Api\V1\ReminderController;
use App\Http\Controllers\Api\V1\FinanceTransactionController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\SkillController;
use App\Http\Controllers\Api\V1\ProjectController;

/*
|--------------------------------------------------------------------------
| API Routes — Xibra Platform
|--------------------------------------------------------------------------
|
| Prefix: /api/v1
| Auth: Laravel Sanctum (Bearer token)
|
*/

// ============================================================================
// Authentication (Public — no login required)
// ============================================================================
Route::prefix('v1/auth')->name('api.v1.auth.')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot-password');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('reset-password');
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum')
        ->name('logout');
    Route::get('/me', [AuthController::class, 'me'])
        ->middleware('auth:sanctum')
        ->name('me');
});

// Password reset route for email notification (used by Laravel's ResetPassword notification)
Route::get('/v1/auth/reset-password/{token}', function ($token, \Illuminate\Http\Request $request) {
    return redirect()->away(
        config('app.frontend_url', 'http://localhost:3000') . "/reset-password?token={$token}&email=" . $request->query('email')
    );
})->name('password.reset');

// ============================================================================
// Personal Dashboard (Protected — Sanctum auth required)
// ============================================================================
Route::middleware('auth:sanctum')->prefix('v1')->name('api.v1.')->group(function () {

    // Schedules
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/schedules/{schedule}', [ScheduleController::class, 'show'])->name('schedules.show');
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

    // Todos
    Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
    Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
    Route::get('/todos/{todo}', [TodoController::class, 'show'])->name('todos.show');
    Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');

    // Reminders
    Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
    Route::post('/reminders', [ReminderController::class, 'store'])->name('reminders.store');
    Route::get('/reminders/{reminder}', [ReminderController::class, 'show'])->name('reminders.show');
    Route::put('/reminders/{reminder}', [ReminderController::class, 'update'])->name('reminders.update');
    Route::delete('/reminders/{reminder}', [ReminderController::class, 'destroy'])->name('reminders.destroy');

    // Finance Transactions
    Route::get('/finance/transactions', [FinanceTransactionController::class, 'index'])->name('finance.transactions.index');
    Route::post('/finance/transactions', [FinanceTransactionController::class, 'store'])->name('finance.transactions.store');
    Route::get('/finance/transactions/{transaction}', [FinanceTransactionController::class, 'show'])->name('finance.transactions.show');
    Route::put('/finance/transactions/{transaction}', [FinanceTransactionController::class, 'update'])->name('finance.transactions.update');
    Route::delete('/finance/transactions/{transaction}', [FinanceTransactionController::class, 'destroy'])->name('finance.transactions.destroy');

    // Skills
    Route::get('/portfolio/skills', [SkillController::class, 'index'])->name('portfolio.skills.index');
    Route::post('/portfolio/skills', [SkillController::class, 'store'])->name('portfolio.skills.store');
    Route::get('/portfolio/skills/{skill}', [SkillController::class, 'show'])->name('portfolio.skills.show');
    Route::put('/portfolio/skills/{skill}', [SkillController::class, 'update'])->name('portfolio.skills.update');
    Route::delete('/portfolio/skills/{skill}', [SkillController::class, 'destroy'])->name('portfolio.skills.destroy');

    // Portfolio Projects
    Route::get('/portfolio/projects', [ProjectController::class, 'index'])->name('portfolio.projects.index');
    Route::post('/portfolio/projects', [ProjectController::class, 'store'])->name('portfolio.projects.store');
    Route::get('/portfolio/projects/{project}', [ProjectController::class, 'show'])->name('portfolio.projects.show');
    Route::put('/portfolio/projects/{project}', [ProjectController::class, 'update'])->name('portfolio.projects.update');
    Route::delete('/portfolio/projects/{project}', [ProjectController::class, 'destroy'])->name('portfolio.projects.destroy');

    // Profile
    Route::get('/profile/me', [ProfileController::class, 'show'])->name('profile.me');
    Route::put('/profile/me', [ProfileController::class, 'update'])->name('profile.update');
});

// ============================================================================
// Profile Photo (Protected — Sanctum auth required)
// ============================================================================
Route::middleware('auth:sanctum')->prefix('v1')->name('api.v1.')->group(function () {
    Route::post('/profile/photo', [AuthController::class, 'uploadProfilePhoto'])->name('profile.photo.upload');
    Route::delete('/profile/photo', [AuthController::class, 'deleteProfilePhoto'])->name('profile.photo.delete');
});

// ============================================================================
// Public Portfolio (Read-only — no login required)
// ============================================================================
Route::prefix('v1/public')->name('api.v1.public.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'showPublic'])->name('profile');
    Route::get('/skills', [SkillController::class, 'publicIndex'])->name('skills');
    Route::get('/projects', [ProjectController::class, 'publicIndex'])->name('projects');
    Route::get('/contact', [ProfileController::class, 'publicContact'])->name('contact');
});
