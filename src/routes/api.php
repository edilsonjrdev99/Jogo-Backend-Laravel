<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthUserController;
use App\Http\Controllers\UserController;

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'store']);
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);

    Route::middleware('auth:api')->group(function () {
        Route::put('/{id}', [UserController::class, 'update']);
        Route::middleware('admin')->group(function () {
            Route::delete('/{id}', [UserController::class, 'destroy']);
        });
    });
});

Route::prefix('auth')->group(function () {
    Route::prefix('users')->group(function () {
        Route::post('/login', [AuthUserController::class, 'login']);
        Route::middleware('auth:api')->post('/logout', [AuthUserController::class, 'logout']);
    });
});

// Rota para testar se a api está ativa
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API está funcionando'
    ]);
});
