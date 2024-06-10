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
use App\Http\Controllers\EstadoUsuarioPremiosController;
use App\Http\Controllers\UsuarioPremiosController;
use App\Http\Controllers\Ideas\IdeasImagenesController;
use App\Http\Controllers\Campos\CamposController;
use App\Http\Controllers\Historial\HistorialController;
use Symfony\Component\CssSelector\Node\FunctionNode;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use App\Models\IdeasImagenes;

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

Route::get('/ideas/{filename}', function ($filename) {
    $path = storage_path('app/ideas/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    $response->header("Content-Type", $type);

    return $response;
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
    Route::get('usuariosAll', [UsersController::class, 'allUsers'])->middleware('active')->middleware('roles');
    Route::get('colaboradores', [UsersController::class, 'colaboradores'])->middleware('active')->middleware('roles');
    Route::post('create', [UsersController::class, 'store'])->middleware('active')->middleware('adminstradores');
    Route::get('show/{id}', [UsersController::class, 'show'])->middleware('active')->middleware('adminstradores')->where('id', '[0-9]+');
    Route::put('update', [UsersController::class, 'update'])->middleware('active')->middleware('adminstradores');
    Route::delete('delete/{id}', [UsersController::class, 'destroy'])->middleware('active')->middleware('adminstradores')->where('id', '[0-9]+');
    Route::post('nombre', [UsersController::class, 'nombre'])->middleware('active')->middleware('adminstradores');
});

//Rutas de ideas
Route::prefix('ideass')->group(function () {
    Route::get('images/{id}', function ($filename) {

        $idea = IdeasImagenes::where('idea_id', $filename)->first();

        if (!$idea) {
            return response()->json(['message' => 'Imagen no encontrada'], 404);
        }

        $filename = $idea->imagen;


        $file = \Illuminate\Support\Facades\Storage::get($filename);

        if (!$file) {
            return response()->json(['message' => 'Imagen no encontrada', 'filename' => $filename], 404);
        }

        return response($file, 200)->header('Content-Type', $idea->mime_type);
    });
    Route::get('list', [IdeasController::class, 'index'])->middleware('active')->middleware('adminstradores');
    Route::post('create', [IdeasController::class, 'create'])->middleware('active')->middleware('roles');
    Route::get('userIdeas', [IdeasController::class, 'userIdeas'])->middleware('active')->middleware('roles');
    Route::get('userIdeasImplementadas/{id}', [IdeasController::class, 'userIdeasImplementadas'])->middleware('active')->middleware('roles');
    Route::get('userideasall/{estatus?}', [IdeasController::class, 'userIdeasAll'])->middleware('active')->middleware('roles')->where('estatus', '[0-9]+');
    Route::get('ideasAll/{estatus?}', [IdeasController::class, 'ideasAll'])->middleware('active')->middleware('adminstradores')->where('estatus', '[0-9]+');
    Route::get('show/{id}', [IdeasController::class, 'show'])->where('id', '[0-9]+')->middleware('active')->middleware('roles');
    Route::put('update', [IdeasController::class, 'update'])->middleware('active')->middleware('adminstradores');
    Route::delete('delete/{id}', [IdeasController::class, 'destroy'])->where('id', '[0-9]+')->middleware('active')->middleware('adminstradores');
    Route::put('puntos', [IdeasController::class, 'puntos'])->middleware('active')->middleware('adminstradores');
    Route::post('titulo', [IdeasController::class, 'titulo'])->middleware('active')->middleware('roles');
    Route::get('ideascontables', [IdeasController::class, 'ideascontables'])->middleware('active')->middleware('roles');
    Route::get('ahorrocontable', [IdeasController::class, 'ahorrocontable'])->middleware('active')->middleware('roles');
    Route::get('puntoscontalbes', [IdeasController::class, 'puntoscontables'])->middleware('active')->middleware('roles');
    Route::get('ahorronocontable', [IdeasController::class, 'ahorronocontable'])->middleware('active')->middleware('roles');
    Route::get('ideasnocontables', [IdeasController::class, 'ideasnocontables'])->middleware('active')->middleware('roles');
});

Route::prefix('ideasimagenes')->group(function () {
    Route::post('create', [IdeasImagenesController::class, 'store'])->middleware('active')->middleware('roles');
    Route::get('show/{id}', [IdeasImagenesController::class, 'show'])->middleware('active')->middleware('roles')->where('id', '[0-9]+');
    Route::put('update', [IdeasImagenesController::class, 'update'])->middleware('active')->middleware('roles');
    Route::delete('delete/{id}', [IdeasImagenesController::class, 'destroy'])->middleware('active')->middleware('roles')->where('id', '[0-9]+');
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
    Route::post('canjear', [ProductoController::class, 'canjear'])->middleware('active')->middleware('roles');
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

//Rutas Usuario Premios
Route::prefix('usuariopremios')->group(function () {
    Route::get('list', [UsuarioPremiosController::class, 'index'])->middleware('active')->middleware('roles');
    Route::post('create', [UsuarioPremiosController::class, 'store'])->middleware('active')->middleware('roles');
    Route::get('show/{id}', [UsuarioPremiosController::class, 'show'])->middleware('active')->middleware('roles')->where('id', '[0-9]+');
    Route::put('update', [UsuarioPremiosController::class, 'update'])->middleware('active')->middleware('roles');
    Route::delete('delete/{id}', [UsuarioPremiosController::class, 'destroy'])->middleware('active')->middleware('roles')->where('id', '[0-9]+');
});

//Rutas Estado Usuario Premios
Route::prefix('estado')->group(function () {
    Route::get('list', [EstadoUsuarioPremiosController::class, 'index'])->middleware('active')->middleware('roles');
    Route::post('create', [EstadoUsuarioPremiosController::class, 'store'])->middleware('active')->middleware('adminstrador');
    Route::get('show/{id}', [EstadoUsuarioPremiosController::class, 'show'])->middleware('active')->middleware('adminstrador')->where('id', '[0-9]+');
    Route::put('update', [EstadoUsuarioPremiosController::class, 'update'])->middleware('active')->middleware('adminstrador');
    Route::delete('delete/{id}', [EstadoUsuarioPremiosController::class, 'destroy'])->middleware('active')->middleware('adminstrador')->where('id', '[0-9]+');
});

//Rutas de campos
Route::prefix('campos')->group(function () {
    Route::get('list', [CamposController::class, 'index'])->middleware('roles');
    Route::post('create', [CamposController::class, 'store'])->middleware('adminstradores');
    Route::get('show/{id}', [CamposController::class, 'show'])->where('id', '[0-9]+')->middleware('roles');
    Route::put('update', [CamposController::class, 'update'])->middleware('adminstradores');
    Route::delete('delete/{id}', [CamposController::class, 'destroy'])->where('id', '[0-9]+')->middleware('adminstradores');
});

//Rutas de historial
Route::prefix('historial')->group(function () {
    Route::get('list', [HistorialController::class, 'index'])->middleware('roles');
    Route::post('create', [HistorialController::class, 'store'])->middleware('roles');
    Route::get('show/{id}', [HistorialController::class, 'show'])->where('id', '[0-9]+')->middleware('roles');
    Route::put('update', [HistorialController::class, 'update'])->middleware('roles');
    Route::delete('delete/{id}', [HistorialController::class, 'destroy'])->where('id', '[0-9]+')->middleware('administadores');
});
