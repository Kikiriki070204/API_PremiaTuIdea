<?php

use App\Http\Controllers\Actividades\ActividadesController;
use App\Http\Controllers\Actividades\EstadoActividadesController;
use App\Http\Controllers\Area\AreaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RolesController;
use App\Http\Controllers\Departamento\DepartamentoController;
use App\Http\Controllers\Equipo\EquipoController;
use App\Http\Controllers\Equipo\UsuarioEquipoController;
use App\Http\Controllers\Ideas\EstadosIdeasController;
use App\Http\Controllers\Ideas\IdeasController;
use App\Http\Controllers\Locancion\LocacionController;
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
    Route::put('register', [AuthController::class, 'registro'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::put('password', [AuthController::class, 'password'])->name('password');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('meplus', [AuthController::class, 'meplus']);
    Route::post('verifyToken', [AuthController::class, 'verifyToken']);
});

//Rutas Usuarios
Route::prefix('users')->group(function () {
    Route::get('list', [UsersController::class, 'index'])->middleware('active')->middleware('adminstradores');
    Route::get('colaboradores', [UsersController::class, 'colaboradores'])->middleware('active')->middleware('roles');
    Route::post('create', [UsersController::class, 'store'])->middleware('active')->middleware('adminstradores');
    Route::get('show/{id}', [UsersController::class, 'show'])->middleware('active')->middleware('adminstradores')->where('id', '[0-9]+');
    Route::put('update', [UsersController::class, 'update'])->middleware('active')->middleware('adminstradores');
    Route::delete('delete/{id}', [UsersController::class, 'destroy'])->middleware('active')->middleware('adminstradores')->where('id', '[0-9]+');
    Route::post('nombre', [UsersController::class, 'nombre'])->middleware('active')->middleware('adminstradores');
});

//Rutas de ideas
Route::prefix('ideas')->group(function () {
    Route::get('list', [IdeasController::class, 'index'])->middleware('active')->middleware('adminstradores');
    Route::post('create', [IdeasController::class, 'create'])->middleware('active')->middleware('roles');
    Route::get('userIdeas', [IdeasController::class, 'userIdeas'])->middleware('active')->middleware('roles');
    Route::get('userideasall/{estatus?}', [IdeasController::class, 'userIdeasAll'])->middleware('active')->middleware('roles')->where('estatus', '[0-9]+');
    Route::get('ideasAll/{estatus?}', [IdeasController::class, 'ideasAll'])->middleware('active')->middleware('adminstradores')->where('estatus', '[0-9]+');
    Route::get('show/{id}', [IdeasController::class, 'show'])->where('id', '[0-9]+')->middleware('active')->middleware('roles');
    Route::put('update', [IdeasController::class, 'update'])->middleware('active')->middleware('adminstradores');
    Route::delete('delete/{id}', [IdeasController::class, 'destroy'])->where('id', '[0-9]+')->middleware('active')->middleware('adminstradores');
    Route::put('puntos', [IdeasController::class, 'puntos'])->middleware('active')->middleware('adminstradores');
    Route::post('titulo', [IdeasController::class, 'titulo'])->middleware('active')->middleware('roles');
});

//Rutas de equipos
Route::prefix('equipos')->group(function () {
    Route::get('list', [EquipoController::class, 'index'])->middleware('active')->middleware('roles');
    Route::post('create', [EquipoController::class, 'store'])->middleware('active')->middleware('roles');
    Route::get('show/{id}', [EquipoController::class, 'show'])->middleware('active')->middleware('roles')->where('id', '[0-9]+');
    Route::get('equipoIdea', [EquipoController::class, 'equipoIdea'])->middleware('active')->middleware('roles');
    Route::put('update', [EquipoController::class, 'update'])->middleware('active')->middleware('roles');
    Route::delete('delete/{id}', [EquipoController::class, 'destroy'])->middleware('active')->middleware('roles')->where('id', '[0-9]+');
});

//Rutas de actividades
Route::prefix('actividades')->group(function () {
    Route::get('list', [ActividadesController::class, 'index'])->middleware('active')->middleware('adminstradores');
    Route::post('create', [ActividadesController::class, 'store'])->middleware('active')->middleware('adminstradores');
    Route::get('show/{id}', [ActividadesController::class, 'show'])->middleware('active')->middleware('adminstradores')->where('id', '[0-9]+');
    Route::put('update', [ActividadesController::class, 'update'])->middleware('active')->middleware('adminstradores');
    Route::get('ideaActividades/{id}', [ActividadesController::class, 'ideaActividades'])->middleware('active')->middleware('adminstrador');
    Route::delete('delete/{id}', [ActividadesController::class, 'destroy'])->middleware('active')->middleware('adminstradores')->where('id', '[0-9]+');
});

//Rutas Roles
Route::prefix('roles')->group(function () {
    Route::get('list', [RolesController::class, 'index'])->middleware('active')->middleware('roles');
    Route::post('create', [RolesController::class, 'store'])->middleware('active')->middleware('adminstrador');
    Route::get('show/{id}', [RolesController::class, 'show'])->middleware('active')->middleware('adminstrador');
    Route::put('update', [RolesController::class, 'update'])->middleware('active')->middleware('adminstrador');
    Route::delete('delete/{id}', [RolesController::class, 'destroy'])->middleware('active')->middleware('adminstrador');
});

//Rutas Areas
Route::prefix('areas')->group(function () {
    Route::get('list', [AreaController::class, 'index'])->middleware('active')->middleware('roles');
    Route::post('create', [AreaController::class, 'store'])->middleware('active')->middleware('adminstrador');
    Route::get('show/{id}', [AreaController::class, 'show'])->middleware('active')->middleware('adminstrador')->where('id', '[0-9]+');
    Route::put('update', [AreaController::class, 'update'])->middleware('active')->middleware('adminstrador');
    Route::delete('delete/{id}', [AreaController::class, 'destroy'])->middleware('active')->middleware('adminstrador')->where('id', '[0-9]+');
});

//Rutas Departamentos
Route::prefix('departamentos')->group(function () {
    Route::get('list', [DepartamentoController::class, 'index'])->middleware('active')->middleware('roles');
    Route::post('create', [DepartamentoController::class, 'store'])->middleware('active')->middleware('adminstradores');
    Route::get('show/{id}', [DepartamentoController::class, 'show'])->middleware('active')->middleware('adminstradores')->where('id', '[0-9]+');
    Route::put('update', [DepartamentoController::class, 'update'])->middleware('active')->middleware('adminstradores');
    Route::delete('delete/{id}', [DepartamentoController::class, 'destroy'])->middleware('active')->middleware('adminstradores')->where('id', '[0-9]+');
});

//Rutas Productos
Route::prefix('productos')->group(function () {
    Route::get('list', [ProductoController::class, 'index'])->middleware('active')->middleware('roles');
    Route::post('create', [ProductoController::class, 'store'])->middleware('active')->middleware('adminstradores');
    Route::get('show/{id}', [ProductoController::class, 'show'])->middleware('active')->middleware('adminstradores')->where('id', '[0-9]+');
    Route::put('update', [ProductoController::class, 'update'])->middleware('active')->middleware('adminstradores');
    Route::delete('delete/{id}', [ProductoController::class, 'destroy'])->middleware('active')->middleware('adminstradores')->where('id', '[0-9]+');
    Route::put('canjear', [ProductoController::class, 'canjear'])->middleware('active')->middleware('roles');
});

//Rutas Usuarios_Equipos
Route::prefix('userteam')->group(function () {
    Route::get('list', [UsuarioEquipoController::class, 'index'])->middleware('active')->middleware('roles');
    Route::post('create', [UsuarioEquipoController::class, 'store'])->middleware('active')->middleware('roles');
    Route::get('show/{id}', [UsuarioEquipoController::class, 'show'])->middleware('active')->middleware('roles')->where('id', '[0-9]+');
    Route::put('update', [UsuarioEquipoController::class, 'update'])->middleware('active')->middleware('roles');
    Route::delete('delete/{id}', [UsuarioEquipoController::class, 'destroy'])->middleware('active')->middleware('roles')->where('id', '[0-9]+');
});

//Rutas Estado Ideas
Route::prefix('estadoideas')->group(function () {
    Route::get('list', [EstadosIdeasController::class, 'index'])->middleware('active')->middleware('roles');
    Route::post('create', [EstadosIdeasController::class, 'store'])->middleware('active')->middleware('adminstrador');
    Route::get('show/{id}', [EstadosIdeasController::class, 'show'])->middleware('active')->middleware('adminstrador')->where('id', '[0-9]+');
    Route::put('update', [EstadosIdeasController::class, 'update'])->middleware('active')->middleware('adminstrador');
    Route::delete('delete/{id}', [EstadosIdeasController::class, 'destroy'])->middleware('active')->middleware('adminstrador')->where('id', '[0-9]+');
});

//Rutas de locaciones
Route::prefix('locaciones')->group(function () {
    Route::get('list', [LocacionController::class, 'index'])->middleware('active')->middleware('roles');
    Route::post('area', [LocacionController::class, 'area'])->middleware('active')->middleware('roles');
    Route::post('create', [LocacionController::class, 'store'])->middleware('active')->middleware('adminstrador');
    Route::get('show/{id}', [LocacionController::class, 'show'])->middleware('active')->middleware('adminstrador')->where('id', '[0-9]+');
    Route::put('update', [LocacionController::class, 'update'])->middleware('active')->middleware('adminstrador');
    Route::delete('delete/{id}', [LocacionController::class, 'destroy'])->middleware('active')->middleware('adminstrador')->where('id', '[0-9]+');
});

//Rutas Estado Actividades
Route::prefix('estadoactividades')->group(function () {
    Route::get('list', [EstadoActividadesController::class, 'index'])->middleware('active')->middleware('roles');
    Route::post('create', [EstadoActividadesController::class, 'store'])->middleware('active')->middleware('adminstrador');
    Route::get('show/{id}', [EstadoActividadesController::class, 'show'])->middleware('active')->middleware('adminstrador')->where('id', '[0-9]+');
    Route::put('update', [EstadoActividadesController::class, 'update'])->middleware('active')->middleware('adminstrador');
    Route::delete('delete/{id}', [EstadoActividadesController::class, 'destroy'])->middleware('active')->middleware('adminstrador')->where('id', '[0-9]+');
});
