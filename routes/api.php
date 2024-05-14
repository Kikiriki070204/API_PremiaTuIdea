<?php

use App\Http\Controllers\Actividades\ActividadesController;
use App\Http\Controllers\Area\AreaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RolesController;
use App\Http\Controllers\Departamento\DepartamentoController;
use App\Http\Controllers\Equipo\EquipoController;
use App\Http\Controllers\Equipo\UsuarioEquipoController;
use App\Http\Controllers\Ideas\IdeasController;
use App\Http\Controllers\Producto\ProductoController;
use App\Http\Controllers\Users\UsersController;
use Symfony\Component\CssSelector\Node\FunctionNode;

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
    Route::get('meplus', [AuthController::class, 'meplus']);
    Route::post('verifyToken', [AuthController::class, 'verifyToken']);
    Route::get('prueba', [AuthController::class, 'prueba']);
});

//Rutas Usuarios
Route::prefix('users')->group(function () {
    Route::get('list', [UsersController::class, 'index']);
    Route::get('colaboradores', [UsersController::class, 'colaboradores']);
    Route::post('create', [UsersController::class, 'store']);
    Route::get('show/{id}', [UsersController::class, 'show']);
    Route::put('update', [UsersController::class, 'update']);
    Route::delete('delete/{id}', [UsersController::class, 'destroy']);
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
    Route::get('equipoIdea', [EquipoController::class, 'equipoIdea']);
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

//Rutas Roles
Route::prefix('roles')->group(function () {
    Route::get('list', [RolesController::class, 'index']);
    Route::post('create', [RolesController::class, 'store']);
    Route::get('show/{id}', [RolesController::class, 'show']);
    Route::put('update', [RolesController::class, 'update']);
    Route::delete('delete/{id}', [RolesController::class, 'destroy']);
});

//Rutas Areas
Route::prefix('areas')->group(function () {
    Route::get('list', [AreaController::class, 'index']);
    Route::post('create', [AreaController::class, 'store']);
    Route::get('show/{id}', [AreaController::class, 'show']);
    Route::put('update', [AreaController::class, 'update']);
    Route::delete('delete/{id}', [AreaController::class, 'destroy']);
});

//Rutas Departamentos
Route::prefix('departamentos')->group(function () {
    Route::get('list', [DepartamentoController::class, 'index']);
    Route::post('create', [DepartamentoController::class, 'store']);
    Route::get('show/{id}', [DepartamentoController::class, 'show']);
    Route::put('update', [DepartamentoController::class, 'update']);
    Route::delete('delete/{id}', [DepartamentoController::class, 'destroy']);
});

//Rutas Productos
Route::prefix('productos')->group(function () {
    Route::get('list', [ProductoController::class, 'index']);
    Route::post('create', [ProductoController::class, 'store']);
    Route::get('show/{id}', [ProductoController::class, 'show']);
    Route::put('update', [ProductoController::class, 'update']);
    Route::delete('delete/{id}', [ProductoController::class, 'destroy']);
});

//Rutas Usuarios_Equipos
Route::prefix('userteam')->group(function () {
    Route::get('list', [UsuarioEquipoController::class, 'index']);
    Route::post('create', [UsuarioEquipoController::class, 'store']);
    Route::get('show/{id}', [UsuarioEquipoController::class, 'show']);
    Route::put('update', [UsuarioEquipoController::class, 'update']);
    Route::delete('delete/{id}', [UsuarioEquipoController::class, 'destroy']);
});
