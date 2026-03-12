<?php
// routes/api.php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\BookController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {

    // ─── Auth — public, no throttle bypass ───────────────────────────────────
    Route::prefix('auth')->name('auth.')->group(function (): void {

        // Strict rate limit on auth endpoints to blunt brute-force attacks.
        Route::middleware('throttle:auth')->group(function (): void {
            Route::post('/register', [AuthController::class, 'register'])->name('register');
            Route::post('/login',    [AuthController::class, 'login'])->name('login');
        });

        Route::middleware(['auth:sanctum', 'throttle:read'])->group(function (): void {
            Route::get('/me',           [AuthController::class, 'me'])->name('me');
            Route::post('/logout',      [AuthController::class, 'logout'])->name('logout');
            Route::post('/logout-all',  [AuthController::class, 'logoutAll'])->name('logout-all');
        });
    });

    // ─── Books — public reads ─────────────────────────────────────────────────
    Route::middleware('throttle:read')->group(function (): void {
        Route::get('/books',        [BookController::class, 'index']);
        Route::get('/books/search', [BookController::class, 'search']);
        Route::get('/books/{book}', [BookController::class, 'show']);
    });

    // ─── Books — authenticated writes ────────────────────────────────────────
    Route::middleware(['auth:sanctum', 'throttle:write'])->group(function (): void {
        Route::post('/books',                [BookController::class, 'store']);
        Route::put('/books/{book}',          [BookController::class, 'update']);
        Route::patch('/books/{book}',        [BookController::class, 'patch']);
        Route::delete('/books/{book}',       [BookController::class, 'destroy']);
        Route::post('/books/{book}/borrow',  [BookController::class, 'borrow']);
        Route::post('/books/{book}/return',  [BookController::class, 'return']);
    });
});
