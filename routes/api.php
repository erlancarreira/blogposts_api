<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rotas públicas de autenticação
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

// Rotas protegidas
Route::middleware('auth:api')->group(function () {
    // Autenticação
    Route::post('auth/logout', [AuthController::class, 'logout']);
    
    // Recursos da API
    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
});

// Rota padrão para URLs não encontradas
Route::fallback(function () {
    abort(404, 'URL não encontrada.');
});
