<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Public\HealthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1/public')->group(function () {
    Route::get('/health', HealthController::class)
        ->name('api.v1.public.health');
});
