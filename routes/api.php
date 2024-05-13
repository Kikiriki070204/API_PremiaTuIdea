<?php

use App\Http\Controllers\Actividades\ActividadesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Equipo\EquipoController;
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

//Rutas de autenticación y registro
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

//Rutas de ideas
Route::prefix('ideas')->group(function () {
    Route::get('list', [IdeasController::class, 'index']);
    Route::post('create', [IdeasController::class, 'create']);
    Route::get('userIdeas', [IdeasController::class, 'userIdeas']);
    Route::get('userideasall', [IdeasController::class, 'userIdeasAll']);
    Route::get('ideasAll', [IdeasController::class, 'ideasAll']);
    Route::get('show/{id}', [IdeasController::class, 'show']);
    Route::put('update', [IdeasController::class, 'update']);
    Route::delete('delete/{id}', [IdeasController::class, 'destroy']);
});

//Rutas de equipos
Route::prefix('equipos')->group(function () {
    Route::get('list', [EquipoController::class, 'index']);
    Route::post('create', [EquipoController::class, 'store']);
    Route::get('show/{id}', [EquipoController::class, 'show']);
    Route::put('update', [EquipoController::class, 'update']);
    Route::delete('delete/{id}', [EquipoController::class, 'destroy']);
});

//Rutas de actividades
Route::prefix('actividades')->group(function () {
    Route::get('list', [ActividadesController::class, 'index']);
    Route::post('create', [ActividadesController::class, 'store']);
    Route::get('show/{id}', [ActividadesController::class, 'show']);
    Route::put('update', [ActividadesController::class, 'update']);
    Route::delete('delete/{id}', [ActividadesController::class, 'destroy']);
});
