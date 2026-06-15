<?php

use App\Http\Controllers\Actividades\ActividadesController;
use App\Http\Controllers\Actividades\EstadoActividadesController;
use App\Http\Controllers\Area\AreaController;
use App\Models\Notificacion;
use App\Models\ProductosImagenes;
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
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\EstadoUsuarioPremiosController;
use App\Http\Controllers\UsuarioPremiosController;
use App\Http\Controllers\Ideas\IdeasImagenesController;
use App\Http\Controllers\Campos\CamposController;
use App\Http\Controllers\Historial\HistorialController;
use Illuminate\Http\Response;
use App\Models\IdeasImagenes;
use App\Http\Controllers\Producto\ProductoController;
use App\Http\Controllers\Anuncios\AnuncioController;
use App\Http\Controllers\Carrusel\CarruselController;
use App\Http\Controllers\Terminos\TerminosController;

Route::get('test', function () {
    return response()->json(['message' => 'Funciona']);
});

// Auth — rutas públicas y protegidas en el mismo grupo
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::put('register', [AuthController::class, 'registro'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::put('password', [AuthController::class, 'password'])->name('password');
    Route::post('verifyToken', [AuthController::class, 'verifyToken']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('meplus', [AuthController::class, 'meplus']);
});

Route::get('areas/list', [AreaController::class, 'index']);
Route::get('roles/list', [RolesController::class, 'index']);
Route::get('departamentos/list', [DepartamentoController::class, 'index']);
Route::get('list', [DepartamentoController::class, 'index']);

// Usuarios
Route::prefix('users')->group(function () {
    Route::get('list', [UsersController::class, 'index'])->middleware('active', 'adminstradores');
    Route::get('usuariosAll', [UsersController::class, 'allUsers'])->middleware('active', 'adminstradores');
    Route::get('usuariosAllExport', [UsersController::class, 'allUsersExport'])->middleware('active', 'adminstradores');
    Route::post('resetAllPuntos', [UsersController::class, 'resetAllPuntos'])->middleware('active', 'adminstradores');
    Route::get('colaboradores', [UsersController::class, 'colaboradores'])->middleware('active');
    Route::get('colaboradoresnew/{id}', [UsersController::class, 'colaboradoresnew'])->middleware('active');
    Route::post('create', [UsersController::class, 'store'])->middleware('active', 'adminstradores');
    Route::get('show/{id}', [UsersController::class, 'show'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
    Route::put('update', [UsersController::class, 'update'])->middleware('active', 'adminstradores');
    Route::delete('delete/{id}', [UsersController::class, 'destroy'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
    Route::post('nombre', [UsersController::class, 'nombre'])->middleware('active', 'adminstradores');
    Route::post('updatePassword', [UsersController::class, 'updatePassword'])->middleware('active');
    Route::post('updatePasswordAdmin', [UsersController::class, 'updatePasswordAdmin'])->middleware('active', 'adminstradores');
});

// Ideas
Route::prefix('ideass')->group(function () {
    Route::get('images/{id}', function ($filename) {
        $idea = IdeasImagenes::where('idea_id', $filename)->first();
        if (!$idea) {
            return response()->json(['message' => 'Imagen no encontrada'], 404);
        }
        $file = \Illuminate\Support\Facades\Storage::get($idea->imagen);
        if (!$file) {
            return response()->json(['message' => 'Imagen no encontrada'], 404);
        }
        return response($file, 200)->header('Content-Type', $idea->mime_type);
    });
    Route::get('resultados/{id}', function ($id) {
        $resultado = \App\Models\IdeasResultados::where('idea_id', $id)->first();
        if (!$resultado) {
            return response()->json(['message' => 'Imagen no encontrada'], 404);
        }
        $file = \Illuminate\Support\Facades\Storage::get($resultado->imagen);
        if (!$file) {
            return response()->json(['message' => 'Imagen no encontrada'], 404);
        }
        return response($file, 200)->header('Content-Type', $resultado->mime_type);
    });
    Route::post('uploadResultado', [IdeasController::class, 'uploadResultado'])->middleware('active');
    Route::get('list', [IdeasController::class, 'index'])->middleware('active', 'adminstradores');
    Route::post('create', [IdeasController::class, 'create'])->middleware('active');
    Route::get('userIdeas', [IdeasController::class, 'userIdeas'])->middleware('active');
    Route::get('userIdeasImplementadas/{id}', [IdeasController::class, 'userIdeasImplementadas'])->middleware('active');
    Route::get('userIdeasTodas/{id}', [IdeasController::class, 'userIdeasTodas'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
    Route::get('userideasall/{estatus?}', [IdeasController::class, 'userIdeasAll'])->middleware('active')->where('estatus', '[0-9]+');
    Route::get('ideasAll/{estatus?}', [IdeasController::class, 'ideasAll'])->middleware('active', 'adminstradores')->where('estatus', '[0-9]+');
    Route::get('ideasAllCategoria/{estatus}/{categoria}', [IdeasController::class, 'ideasAllCategoria'])->middleware('active', 'adminstradores')->where('estatus', '[0-9]+');
    Route::get('show/{id}', [IdeasController::class, 'show'])->where('id', '[0-9]+')->middleware('active');
    Route::put('update', [IdeasController::class, 'update'])->middleware('active', 'adminstradores');
    Route::delete('delete/{id}', [IdeasController::class, 'destroy'])->where('id', '[0-9]+')->middleware('active', 'adminstradores');
    Route::put('puntos', [IdeasController::class, 'puntos'])->middleware('active', 'adminstradores');
    Route::put('bonos', [IdeasController::class, 'asignarBonos'])->middleware('active', 'adminstradores');
    Route::post('titulo', [IdeasController::class, 'titulo'])->middleware('active');
    Route::put('tipoCambio', [IdeasController::class, 'actualizarTipoCambio'])->middleware('active', 'adminstradores');
    Route::get('tipoCambio', [IdeasController::class, 'obtenerTipoCambio'])->middleware('active', 'adminstradores');
    Route::post('reporteTrimestral', [IdeasController::class, 'reporteIdeasVsUsuarios'])->middleware('active');
    Route::post('reporteMensual', [IdeasController::class, 'reporteParticipacionEmpleados'])->middleware('active');
    Route::post('puntoscontables', [IdeasController::class, 'puntoscontables'])->middleware('active');
    Route::post('puntosnocontables', [IdeasController::class, 'puntosnocontables'])->middleware('active');
    Route::get('top10', [IdeasController::class, 'top10Usuarios'])->middleware('active');
    Route::get('puntosContablesHistoricos', [IdeasController::class, 'puntosContablesHistoricos'])->middleware('active');
    Route::get('puntosNoContablesHistoricos', [IdeasController::class, 'puntosNoContablesHistoricos'])->middleware('active');
    Route::post('ideastotales', [IdeasController::class, 'ideastotales'])->middleware('active');
    Route::post('ideascontables', [IdeasController::class, 'ideascontables'])->middleware('active');
    Route::post('ideasnocontables', [IdeasController::class, 'ideasnocontables'])->middleware('active');
    Route::get('ideasTotalesHistoricas', [IdeasController::class, 'ideasTotalesHistoricas'])->middleware('active');
    Route::get('ideasContablesHistoricas', [IdeasController::class, 'ideasContablesHistoricas'])->middleware('active');
    Route::get('ideasNoContablesHistoricas', [IdeasController::class, 'ideasNoContablesHistoricas'])->middleware('active');
    Route::get('ideasHistoricasEstatusArea', [IdeasController::class, 'ideasHistoricasEstatusArea'])->middleware('active');
    Route::post('ideasHistoricasEstatusArea', [IdeasController::class, 'ideasHistoricasEstatusAreaFiltradas'])->middleware('active');
    Route::get('ideasHistoricasEstatusCategoria', [IdeasController::class, 'ideasHistoricasEstatusCategoria'])->middleware('active');
    Route::post('ideasHistoricasEstatusCategoria', [IdeasController::class, 'ideasHistoricasEstatusCategoriaFiltradas'])->middleware('active');
    Route::get('ahorroHistorico', [IdeasController::class, 'ahorroHistorico'])->middleware('active');
    Route::post('ahorrocontable', [IdeasController::class, 'ahorrocontable'])->middleware('active');
    Route::get('ahorroHistoricoCategoria', [IdeasController::class, 'ahorrosHistoricosPorCategoria'])->middleware('active');
    Route::post('ahorroHistoricoCategoriaFechas', [IdeasController::class, 'ahorrosHistoricosPorCategoriaFechas'])->middleware('active');
    Route::get('ideasMensualesPorAnio', [IdeasController::class, 'ideasMensualesPorAnio'])->middleware('active');
    Route::get('ideasMensualesPorAnioYArea', [IdeasController::class, 'ideasMensualesPorAnioYArea'])->middleware('active');
});

// Ideas Imágenes
Route::prefix('ideasimagenes')->group(function () {
    Route::post('create', [IdeasImagenesController::class, 'store'])->middleware('active');
    Route::get('show/{id}', [IdeasImagenesController::class, 'show'])->middleware('active')->where('id', '[0-9]+');
    Route::put('update', [IdeasImagenesController::class, 'update'])->middleware('active');
    Route::delete('delete/{id}', [IdeasImagenesController::class, 'destroy'])->middleware('active')->where('id', '[0-9]+');
});

// Equipos
Route::prefix('equipos')->group(function () {
    Route::get('list', [EquipoController::class, 'index'])->middleware('active');
    Route::post('create', [EquipoController::class, 'store'])->middleware('active');
    Route::get('show/{id}', [EquipoController::class, 'show'])->middleware('active')->where('id', '[0-9]+');
    Route::get('equipoIdea', [EquipoController::class, 'equipoIdea'])->middleware('active');
    Route::put('update', [EquipoController::class, 'update'])->middleware('active');
    Route::delete('delete/{id}', [EquipoController::class, 'destroy'])->middleware('active')->where('id', '[0-9]+');
});

// Actividades
Route::prefix('actividades')->group(function () {
    Route::get('list', [ActividadesController::class, 'index'])->middleware('active', 'adminstradores');
    Route::post('create', [ActividadesController::class, 'store'])->middleware('active', 'adminstradores');
    Route::get('show/{id}', [ActividadesController::class, 'show'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
    Route::put('update', [ActividadesController::class, 'update'])->middleware('active', 'adminstradores');
    Route::get('ideaActividades/{id}', [ActividadesController::class, 'ideaActividades'])->middleware('active');
    Route::delete('delete/{id}', [ActividadesController::class, 'destroy'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
});

// Roles
Route::prefix('roles')->group(function () {
    Route::post('create', [RolesController::class, 'store'])->middleware('active', 'adminstradores');
    Route::get('show/{id}', [RolesController::class, 'show'])->middleware('active', 'adminstradores');
    Route::put('update', [RolesController::class, 'update'])->middleware('active', 'adminstradores');
    Route::delete('delete/{id}', [RolesController::class, 'destroy'])->middleware('active', 'adminstradores');
});

// Áreas
Route::prefix('areas')->group(function () {
    Route::post('create', [AreaController::class, 'store'])->middleware('active', 'adminstradores');
    Route::get('show/{id}', [AreaController::class, 'show'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
    Route::put('update', [AreaController::class, 'update'])->middleware('active', 'adminstradores');
    Route::delete('delete/{id}', [AreaController::class, 'destroy'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
});

// Departamentos
Route::prefix('departamentos')->group(function () {
    Route::post('create', [DepartamentoController::class, 'store'])->middleware('active', 'adminstradores');
    Route::get('show/{id}', [DepartamentoController::class, 'show'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
    Route::put('update', [DepartamentoController::class, 'update'])->middleware('active', 'adminstradores');
    Route::delete('delete/{id}', [DepartamentoController::class, 'destroy'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
});

// Productos / Premios
Route::prefix('productos')->group(function () {
    Route::get('images/{id}', function ($filename) {
        $producto = ProductosImagenes::where('producto_id', $filename)->first();
        if (!$producto) {
            return response()->json(['message' => 'Imagen no encontrada'], 404);
        }
        $file = \Illuminate\Support\Facades\Storage::get($producto->imagen);
        if (!$file) {
            return response()->json(['message' => 'Imagen no encontrada'], 404);
        }
        return response($file, 200)->header('Content-Type', $producto->mime_type);
    });
    Route::get('list', [ProductoController::class, 'index'])->middleware('active');
    Route::get('list/asc', [ProductoController::class, 'indexAsc'])->middleware('active');
    Route::get('list/dsc', [ProductoController::class, 'indexDsc'])->middleware('active');
    Route::post('create', [ProductoController::class, 'store'])->middleware('active', 'adminstradores');
    Route::get('show/{id}', [ProductoController::class, 'show'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
    Route::post('update', [ProductoController::class, 'update'])->middleware('active', 'adminstradores');
    Route::delete('delete/{id}', [ProductoController::class, 'destroy'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
    Route::post('canjear', [ProductoController::class, 'canjear'])->middleware('active');
});

// Equipos de usuarios
Route::prefix('userteam')->group(function () {
    Route::get('list', [UsuarioEquipoController::class, 'index'])->middleware('active');
    Route::post('create', [UsuarioEquipoController::class, 'store'])->middleware('active');
    Route::get('show/{id}', [UsuarioEquipoController::class, 'show'])->middleware('active')->where('id', '[0-9]+');
    Route::put('update', [UsuarioEquipoController::class, 'update'])->middleware('active');
    Route::delete('delete/{id}', [UsuarioEquipoController::class, 'destroy'])->middleware('active')->where('id', '[0-9]+');
});

// Estado de Ideas
Route::prefix('estadoideas')->group(function () {
    Route::get('list', [EstadosIdeasController::class, 'index'])->middleware('active');
    Route::post('create', [EstadosIdeasController::class, 'store'])->middleware('active', 'adminstradores');
    Route::get('show/{id}', [EstadosIdeasController::class, 'show'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
    Route::put('update', [EstadosIdeasController::class, 'update'])->middleware('active', 'adminstradores');
    Route::delete('delete/{id}', [EstadosIdeasController::class, 'destroy'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
});

// Locaciones
Route::prefix('locaciones')->group(function () {
    Route::get('list', [LocacionController::class, 'index'])->middleware('active');
    Route::post('area', [LocacionController::class, 'area']);
    Route::post('create', [LocacionController::class, 'store'])->middleware('active', 'adminstradores');
    Route::get('show/{id}', [LocacionController::class, 'show'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
    Route::put('update', [LocacionController::class, 'update'])->middleware('active', 'adminstradores');
    Route::delete('delete/{id}', [LocacionController::class, 'destroy'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
});

// Estado de Actividades
Route::prefix('estadoactividades')->group(function () {
    Route::get('list', [EstadoActividadesController::class, 'index'])->middleware('active');
    Route::post('create', [EstadoActividadesController::class, 'store'])->middleware('active', 'adminstradores');
    Route::get('show/{id}', [EstadoActividadesController::class, 'show'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
    Route::put('update', [EstadoActividadesController::class, 'update'])->middleware('active', 'adminstradores');
    Route::delete('delete/{id}', [EstadoActividadesController::class, 'destroy'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
});

// Premios de usuarios
Route::prefix('usuariopremios')->group(function () {
    Route::get('list', [UsuarioPremiosController::class, 'index'])->middleware('active');
    Route::get('list/admin', [UsuarioPremiosController::class, 'indexAdmin'])->middleware('active');
    Route::post('create', [UsuarioPremiosController::class, 'store'])->middleware('active');
    Route::get('show/{id}', [UsuarioPremiosController::class, 'show'])->middleware('active')->where('id', '[0-9]+');
    Route::put('update', [UsuarioPremiosController::class, 'update'])->middleware('active');
    Route::delete('delete/{id}', [UsuarioPremiosController::class, 'destroy'])->middleware('active')->where('id', '[0-9]+');
});

// Estado de premios de usuarios
Route::prefix('estado')->group(function () {
    Route::get('list', [EstadoUsuarioPremiosController::class, 'index'])->middleware('active');
    Route::post('create', [EstadoUsuarioPremiosController::class, 'store'])->middleware('active', 'adminstradores');
    Route::get('show/{id}', [EstadoUsuarioPremiosController::class, 'show'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
    Route::put('update', [EstadoUsuarioPremiosController::class, 'update'])->middleware('active', 'adminstradores');
    Route::delete('delete/{id}', [EstadoUsuarioPremiosController::class, 'destroy'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
    Route::get('resumenPremios', [EstadoUsuarioPremiosController::class, 'resumenPremios'])->middleware('active');
    Route::get('top10ProductosEntregados', [EstadoUsuarioPremiosController::class, 'top10ProductosEntregados'])->middleware('active');
});

// Campos
Route::prefix('campos')->group(function () {
    Route::get('list', [CamposController::class, 'index'])->middleware('active');
    Route::get('monetario/{num}', [CamposController::class, 'monetario'])->where('num', '[0-9]+')->middleware('active');
    Route::post('create', [CamposController::class, 'store'])->middleware('active', 'adminstradores');
    Route::get('show/{id}', [CamposController::class, 'show'])->where('id', '[0-9]+')->middleware('active');
    Route::put('update', [CamposController::class, 'update'])->middleware('active', 'adminstradores');
    Route::delete('delete/{id}', [CamposController::class, 'destroy'])->where('id', '[0-9]+')->middleware('active', 'adminstradores');
});

// Historial
Route::prefix('historial')->group(function () {
    Route::post('list', [HistorialController::class, 'index'])->middleware('active');
    Route::post('create', [HistorialController::class, 'store'])->middleware('active');
    Route::get('periodo', [HistorialController::class, 'create'])->middleware('active');
    Route::get('show/{id}', [HistorialController::class, 'show'])->where('id', '[0-9]+')->middleware('active');
    Route::put('update', [HistorialController::class, 'update'])->middleware('active');
    Route::delete('delete/{id}', [HistorialController::class, 'destroy'])->where('id', '[0-9]+')->middleware('active', 'adminstradores');
});

// Carrusel
Route::prefix('carrusel')->group(function () {
    Route::get('list', [CarruselController::class, 'index']);
    Route::get('image/{id}', [CarruselController::class, 'image'])->where('id', '[0-9]+');
    Route::get('admin', [CarruselController::class, 'adminIndex'])->middleware('active', 'adminstradores');
    Route::post('store', [CarruselController::class, 'store'])->middleware('active', 'adminstradores');
    Route::delete('delete/{id}', [CarruselController::class, 'destroy'])->middleware('active', 'adminstradores')->where('id', '[0-9]+');
});

// Anuncios
Route::prefix('anuncios')->group(function () {
    Route::get('current', [AnuncioController::class, 'show'])->middleware('active');
    Route::get('config', [AnuncioController::class, 'config'])->middleware('active', 'adminstradores');
    Route::put('update', [AnuncioController::class, 'update'])->middleware('active', 'adminstradores');
});

// Términos y Condiciones
Route::prefix('terminos')->group(function () {
    Route::get('/', [TerminosController::class, 'index'])->middleware('active');
    Route::get('imagen/{id}', [TerminosController::class, 'imagen'])->where('id', '[0-9]+'); // público: <img> no puede enviar JWT
    Route::put('{seccion}', [TerminosController::class, 'update'])->middleware('active');
    Route::post('{seccion}/imagen', [TerminosController::class, 'uploadImagen'])->middleware('active');
    Route::delete('imagen/{id}', [TerminosController::class, 'deleteImagen'])->where('id', '[0-9]+')->middleware('active');
});

// Bonos
Route::get('usuariosBonos', [UsuarioPremiosController::class, 'usuariosBonos'])->middleware('active');

// Notificaciones
Route::get('notificaciones', function () {
    try {
        $user = \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::parseToken()->authenticate();
    } catch (\Exception $e) {
        return response()->json(['error' => 'No autorizado'], 401);
    }
    $notificaciones = Notificacion::where('usuario_id', $user->id)
        ->where('leida', false)
        ->orderBy('created_at', 'desc')
        ->get();
    return response()->json(['notificaciones' => $notificaciones], 200);
});

Route::post('notificaciones/leidas', function (Request $request) {
    try {
        $user = \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::parseToken()->authenticate();
    } catch (\Exception $e) {
        return response()->json(['error' => 'No autorizado'], 401);
    }
    Notificacion::where('usuario_id', $user->id)
        ->where('leida', false)
        ->update(['leida' => true]);
    return response()->json(['msg' => 'Notificaciones marcadas como leídas']);
});
