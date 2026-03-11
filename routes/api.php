<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\BookController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|

Rate limiting is configured in RouteServiceProvider
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::prefix('v1')->group(function (): void {

    // Public read endpoints — 60 req/min per IP
    Route::middleware('throttle:read')->group(function (): void {
        Route::get('/books', [BookController::class, 'index']);
        Route::get('/books/search', [BookController::class, 'search']);
        Route::get('/books/{book}', [BookController::class, 'show']);
    });

    // Authenticated write endpoints — 20 req/min per IP
    Route::middleware(['auth:sanctum', 'throttle:write'])->group(function (): void {
        Route::post('/books', [BookController::class, 'store']);
        Route::put('/books/{book}', [BookController::class, 'update']);
        Route::patch('/books/{book}', [BookController::class, 'patch']);
        Route::delete('/books/{book}', [BookController::class, 'destroy']);
        Route::post('/books/{book}/borrow', [BookController::class, 'borrow']);
        Route::post('/books/{book}/return', [BookController::class, 'return']);
    });
});
