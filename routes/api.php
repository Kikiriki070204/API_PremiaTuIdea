<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Ideas\IdeasController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('auth')->group(function () {
    Route::get('hola', [AuthController::class, 'hola']);
    Route::put('register', [AuthController::class, 'registro'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::put('password', [AuthController::class, 'password'])->name('password');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('me', [AuthController::class, 'me']);
    Route::post('verifyToken', [AuthController::class, 'verifyToken']);
    Route::get('prueba', [AuthController::class, 'prueba']);
});

Route::prefix('ideas')->group(function () {
    Route::get('list', [IdeasController::class, 'index']);
    Route::post('create', [IdeasController::class, 'create']);
    Route::get('show/{id}', [IdeasController::class, 'show']);
    Route::put('update', [IdeasController::class, 'update']);
});
