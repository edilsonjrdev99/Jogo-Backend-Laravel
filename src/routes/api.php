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
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

Route::prefix('auth')->group(function () {
    Route::prefix('users')->group(function () {
        Route::post('/login', [AuthUserController::class, 'login']);
    });
});
