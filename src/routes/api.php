<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthUserController;
use App\Http\Controllers\UserController;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API está funcionando'
    ]);
});

Route::prefix('users')->group(function () {
    // Rotas públicas sem autenticação
    Route::post('/', [UserController::class, 'store']);        // Registro público
    Route::get('/', [UserController::class, 'index']);         // Lista pública
    Route::get('/{id}', [UserController::class, 'show']);      // Detalhe público

    // Rotas protegidas, requer autenticação
    Route::middleware('auth:api')->group(function () {
        Route::put('/{id}', [UserController::class, 'update']); // Auth + policy ownership check

        // Rotas exclusivas de admin
        Route::middleware('admin')->group(function () {
            Route::delete('/{id}', [UserController::class, 'destroy']); // Admin only
        });
    });
});

Route::prefix('auth')->group(function () {
    Route::prefix('users')->group(function () {
        Route::post('/login', [AuthUserController::class, 'login']);
        Route::middleware('auth:api')->post('/logout', [AuthUserController::class, 'logout']);
    });
});
