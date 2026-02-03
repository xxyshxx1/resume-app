<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResumeController;
use App\Models\ResumeSection;
use Illuminate\Support\Facades\Route;

// SPA catch-all route - serves the Vue app
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');

// API Routes
Route::prefix('api')->group(function () {
    // Auth routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user'])->middleware('auth');

    // Resume routes (protected)
    Route::middleware('auth')->group(function () {
        Route::get('/resume', [ResumeController::class, 'index']);
        Route::post('/resume', [ResumeController::class, 'store']);
        Route::put('/resume/{section}', [ResumeController::class, 'update']);
        Route::delete('/resume/{section}', [ResumeController::class, 'destroy']);
        Route::post('/resume/initialize', [ResumeController::class, 'initializeDefaults']);
    });

    // Public resume view (for sharing)
    Route::get('/resume/user/{userId}', [ResumeController::class, 'show']);
});

// CSRF cookie route for SPA
Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});
