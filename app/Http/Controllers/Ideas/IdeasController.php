<?php

namespace App\Http\Controllers\Ideas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Idea;
use Illuminate\Support\Facades\Validator;
use App\Models\Equipo;
use App\Models\Usuario_Equipo;
use App\Models\Usuario;
use App\Models\Actividades;
use Illuminate\Support\Facades\DB;
use App\Models\IdeasImagenes;
use Illuminate\Support\Str;
use App\Models\Historial;
use App\Models\Campos_Idea;
use App\Models\UsuariosPeriodo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Exception;
use App\Http\Controllers\Ideas\IdeasImagenesController;
use Yaza\LaravelGoogleDriveStorage\Gdrive;
use Illuminate\Support\Facades\Mail;
use App\Mail\aceptacion;
use App\Mail\rechazo;
use App\Models\User;

class IdeasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $user = auth()->user();

        if ($user) {
            $ideas = Idea::all();

            return response()->json(["ideas" => $ideas], 200);
        }
        return response()->json(["msg" => "No estás autorizado"], 401);
    }

    public function userIdeas()
    {
        $user = auth('api')->user();

        if ($user) {
            $ideas = DB::table('ideas')
                ->join('estado_ideas', 'ideas.estatus', '=', 'estado_ideas.id')
                ->join('equipos', 'ideas.id', '=', 'equipos.id_idea')
                ->join('usuarios_equipos', 'equipos.id', '=', 'usuarios_equipos.id_equipo')
                ->select('ideas.*', 'estado_ideas.nombre as estatus_idea')
                ->where('usuarios_equipos.id_usuario', $user->id)
                ->where('ideas.estatus', 3)
                ->where('usuarios_equipos.is_active', 1)
                ->get();

            return response()->json(["ideas" => $ideas], 200);
        }
        return response()->json(["msg" => "Usuario no Encontrado"], 401);
    }

    public function userIdeasImplementadas($id)
    {

        $user = Usuario::find($id);

        if ($user) {
            $ideas = DB::table('ideas')
                ->join('estado_ideas', 'ideas.estatus', '=', 'estado_ideas.id')
                ->join('equipos', 'ideas.id', '=', 'equipos.id_idea')
                ->join('usuarios_equipos', 'equipos.id', '=', 'usuarios_equipos.id_equipo')
                ->select('ideas.*', 'estado_ideas.nombre as estatus_idea')
                ->where('usuarios_equipos.id_usuario', $id)
                ->where('ideas.estatus', 3)
                ->where('usuarios_equipos.is_active', 1)
                ->get();

            return response()->json(["ideas" => $ideas], 200);
        }
        return response()->json(["msg" => "Usuario no Encontrado"], 401);
    }

    public function userIdeasAll($estatus = null)
    {
        $user = auth('api')->user();

        if ($user) {
            $validate = Validator::make(
                ['estatus' => $estatus],
                [
                    'estatus' => 'nullable|exists:estado_ideas,id'
                ]
            );

            if ($validate->fails()) {
                return response()->json([
                    "errors" => $validate->errors(),
                    "msg" => "Errores de validación"
                ], 422);
            }


            if ($estatus === null) {
                $ideas = DB::table('ideas')
                    ->join('estado_ideas', 'ideas.estatus', '=', 'estado_ideas.id')
                    ->join('equipos', 'ideas.id', '=', 'equipos.id_idea')
                    ->join('usuarios_equipos', 'equipos.id', '=', 'usuarios_equipos.id_equipo')
                    ->select('ideas.*', 'estado_ideas.nombre as estatus_idea')
                    ->where('usuarios_equipos.id_usuario', $user->id)
                    ->where('usuarios_equipos.is_active', 1)
                    ->get();

                return response()->json(["ideas" => $ideas], 200);
            }
            $ideas = DB::table('ideas')
                ->join('estado_ideas', 'ideas.estatus', '=', 'estado_ideas.id')
                ->join('equipos', 'ideas.id', '=', 'equipos.id_idea')
                ->join('usuarios_equipos', 'equipos.id', '=', 'usuarios_equipos.id_equipo')
                ->select('ideas.*', 'estado_ideas.nombre as estatus_idea')
                ->where('usuarios_equipos.id_usuario', $user->id)
                ->where('usuarios_equipos.is_active', 1)
                ->where('ideas.estatus', $estatus)
                ->get();

            return response()->json(["ideas" => $ideas], 200);
        }
        return response()->json(["msg" => "Usuario no Encontrado"], 401);
    }

    public function ideasAll($estatus = null)
    {
        $validate = Validator::make(
            ['estatus' => $estatus],
            [
                'estatus' => 'nullable|exists:estado_ideas,id'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        if ($estatus == null) {
            $ideas = Idea::all();

            return response()->json(["ideas" => $ideas], 200);
        }

        $ideas = Idea::where('estatus', $estatus)->get();

        return response()->json(["ideas" => $ideas], 200);
    }

    public function create(Request $request)
    {
        $user = auth('api')->user();

        if ($user) {
            $request->merge(['area_id' => intval($request->area_id)]);

            $validate = Validator::make(
                $request->all(),
                [
                    'titulo' => 'required|min:5',
                    'antecedentes' => 'required| max:2000',
                    'condiciones' => 'required|file|image|mimes:jpeg,png,jpg|max:4900',
                    'propuesta' => 'required|max:2000',
                    'fecha_inicio' => 'required|date',
                    'area_id' => 'required|integer|exists:areas,id',
                ]
            );

            if ($validate->fails()) {
                return response()->json([
                    "errors" => $validate->errors(),
                    "msg" => "Errores de validación"
                ], 422);
            }

            DB::beginTransaction();

            try {
                $idea = new Idea();

                $idea->user_id = $user->id;
                $idea->titulo = $request->titulo;
                $idea->antecedente = $request->antecedentes;
                $idea->propuesta = $request->propuesta;
                $idea->fecha_inicio = $request->fecha_inicio;
                $idea->area_id = $request->area_id;
                $idea->contable = 0;
                $idea->save();

                // Guardar la imagen en el sistema de archivos
                $file = $request->file('condiciones');
                $originalFilename = $request->file('condiciones')->getClientOriginalName();
                $uniqueFilename = Str::uuid() . '.' . pathinfo($originalFilename, PATHINFO_EXTENSION);
                $path = $file->storePubliclyAs('public/images', $uniqueFilename);

                $imagen = new IdeasImagenes();
                $imagen->idea_id = $idea->id;
                $imagen->imagen = $path;
                $imagen->mime_type = $request->file('condiciones')->getMimeType();
                $imagen->save();

                $Equipo = new Equipo();
                $Equipo->id_idea = $idea->id;
                $Equipo->save();

                $userTeam = new Usuario_Equipo();
                $userTeam->id_usuario = $user->id;
                $userTeam->id_equipo = $Equipo->id;
                $userTeam->save();

                DB::commit();

                return $idea;
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json(["msg" => "Error al guardar la idea e imagen"], 500);
            }
        }
        return response()->json(["msg" => "No estás autorizado"], 401);
    }


    public function show($id)
    {
        //$idea = Idea::where('id', $id)->first();
        $idea = DB::table('ideas')
            ->join('estado_ideas', 'ideas.estatus', '=', 'estado_ideas.id')
            ->select('ideas.*', 'estado_ideas.nombre as estatus_idea')
            ->where('ideas.id', $id)
            ->first();

        if (!$idea) {
            return response()->json(["msg" => "Idea no encontrada"], 404);
        }

        $colaboradores = DB::table('usuarios_equipos')
            ->join('equipos', 'usuarios_equipos.id_equipo', '=', 'equipos.id')
            ->join('usuarios', 'usuarios_equipos.id_usuario', '=', 'usuarios.id')
            ->join('ideas', 'equipos.id_idea', '=', 'ideas.id')
            ->select('usuarios.nombre', 'usuarios.id')
            ->where('ideas.id', $idea->id)
            ->where('usuarios_equipos.is_active', true)
            ->get();

        $campos = DB::table('campos__ideas')
            ->join('campos', 'campos__ideas.campo_id', '=', 'campos.id')
            ->select('campos.id', 'campos.nombre')
            ->where('campos__ideas.idea_id', $idea->id)
            ->get();

        if ($idea) {
            return response()->json(["idea" => $idea, "colaboradores" => $colaboradores, "campos" => $campos], 200);
        }
        return response()->json(["msg" => "Idea no encontrada"], 404);
    }

    public function update(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'id' => 'required|integer|exists:ideas,id',
                'titulo' => 'required|min:5',
                'antecedentes' => 'required| max: 2000',
                'propuesta' => 'required|max: 2000',
                'puntos' => 'required|integer',
                'estatus' => 'required|integer|exists:estado_ideas,id',
                'fecha_fin' => 'nullable|date',
                'ahorro' => 'nullable|numeric',
                'contable' => 'nullable|boolean',
                'campos_id' => 'nullable|array',
                'campos_id.*' => 'integer|exists:campos,id'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $idea = Idea::where('id', $request->id)->first();

        if (!$idea) {
            return response()->json(["msg" => "Idea no encontrada"], 404);
        }

        $idea->titulo = $request->titulo;
        $idea->antecedente = $request->antecedentes;
        $idea->propuesta = $request->propuesta;
        $idea->estatus = $request->estatus;
        $idea->puntos = $request->puntos;
        $idea->fecha_fin = $request->fecha_fin;
        $idea->ahorro = $request->ahorro;
        $idea->contable = $request->contable;
        $idea->save();


        if (!is_null($request->campos_id)) {
            foreach ($request->campos_id as $campo) {
                if (Campos_Idea::where('idea_id', $idea->id)->where('campo_id', $campo)->first()) {
                    continue;
                }
                $campoidea = new Campos_Idea();
                $campoidea->idea_id = $idea->id;
                $campoidea->campo_id = $campo;
                $campoidea->save();
            }
        }

        return response()->json(["msg" => "Idea actualizada correctamente"], 200);
    }

    public function destroy($id)
    {
        $idea = Idea::where('id', $id)->first();

        if ($idea) {
            $Equipo = Equipo::where('id_idea', $idea->id)->first();
            if ($Equipo) {
                $usuariosEquipo = Usuario_Equipo::where('id_equipo', $Equipo->id)->get();
                foreach ($usuariosEquipo as $usuario) {
                    $usuario->delete();
                }
                $Equipo->delete();
            }

            $ideasImagenes = IdeasImagenes::where('idea_id', $idea->id)->get();
            foreach ($ideasImagenes as $imagen) {
                Storage::delete($imagen->imagen);
                $imagen->delete();
            }

            $actividades = Actividades::where('id_idea', $idea->id)->get();
            foreach ($actividades as $actividad) {
                $actividad->delete();
            }

            $idea->delete();

            return response()->json(["msg" => "Idea y sus recursos asociados eliminados correctamente"], 200);
        }

        return response()->json(["msg" => "Idea no encontrada"], 404);
    }


    public function puntos(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'id' => 'required|integer|exists:ideas,id',
                'id_usuarios' => 'required|array',
                'id_usuarios.*' => 'integer|exists:usuarios,id',
                'puntos' => 'required|array',
                'puntos.*' => 'integer',
                'fecha' => 'required|date',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        if (count($request->id_usuarios) != count($request->puntos)) {
            return response()->json([
                "msg" => "Los arrays de ID de usuarios y puntos deben tener la misma longitud"
            ], 422);
        }

        DB::beginTransaction();
        try {
            $puntosIdea = 0;
            for ($i = 0; $i < count($request->id_usuarios); $i++) {
                $usuario = Usuario::find($request->id_usuarios[$i]);
                if (!$usuario) {
                    throw new \Exception("Usuario no encontrado");
                }
                $usuario->puntos += $request->puntos[$i];
                $usuario->save();

                $historial = Historial::firstOrNew(['user_id' => $request->id_usuarios[$i]]);
                $historial->puntos += $request->puntos[$i];
                $historial->save();

                $puntosIdea += $request->puntos[$i];

                // Crear una nueva instancia de UsuariosPeriodo dentro del bucle
                $userperiodo = new UsuariosPeriodo();
                $userperiodo->user_id = $request->id_usuarios[$i];
                $userperiodo->puntos = $request->puntos[$i];
                $userperiodo->fecha = $request->fecha;
                $userperiodo->save();
            }

            $idea = Idea::find($request->id);
            if (!$idea) {
                throw new \Exception("Idea no encontrada");
            }
            $idea->puntos += $puntosIdea;
            $idea->save();

            DB::commit();
            return response()->json(["msg" => "Puntos asignados correctamente"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["msg" => "Error al asignar puntos", "error" => $e->getMessage()], 500);
        }
    }

    public function ideascontables(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date'
            ]
        );

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        $totalIdeas = DB::table('ideas')
            ->where('contable', true)
            ->where('ideas.estatus', 3)
            ->whereBetween('ideas.fecha_fin', [$fechaInicio, $fechaFin])
            ->count();

        $totalideasPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) use ($fechaInicio, $fechaFin) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where('ideas.contable', true)
                    ->where('ideas.estatus', 3)
                    ->whereBetween('ideas.fecha_fin', [$fechaInicio, $fechaFin]);
            })
            ->select('areas.nombre as nombre_area', DB::raw('COALESCE(COUNT(ideas.id), 0) as total_ideas'))
            ->groupBy('areas.id', 'areas.nombre')
            ->orderBy('areas.nombre', 'asc')
            ->get();

        $respuesta = [
            'total_ideas' => $totalIdeas,
            'ideas_por_area' => $totalideasPorArea
        ];

        return response()->json($respuesta, 200);
    }

    public function ahorrocontable(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date'
            ]
        );

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        $totalAhorros = DB::table('ideas')
            ->where('contable', true)
            ->where('ideas.estatus', 3)
            ->whereBetween('ideas.fecha_fin', [$fechaInicio, $fechaFin])
            ->sum('ahorro');
        $ahorrosPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) use ($fechaInicio, $fechaFin) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where('ideas.contable', true)
                    ->where('ideas.estatus', 3)
                    ->whereBetween('ideas.fecha_fin', [$fechaInicio, $fechaFin]);
            })
            ->select('areas.nombre as nombre_area', DB::raw('COALESCE(SUM(ideas.ahorro),0) as total_ahorros'))
            ->groupBy('ideas.area_id', 'areas.nombre')
            ->orderBy('areas.nombre', 'asc')
            ->get();

        $respuesta = [
            'total_ahorros' => $totalAhorros,
            'ahorros_por_area' => $ahorrosPorArea,
        ];

        return response()->json(["msg" => $respuesta, 200]);
    }

    public function puntoscontables(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date'
            ]
        );

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        $totalPuntos = DB::table('ideas')
            ->where('contable', true)
            ->where('ideas.estatus', 3)
            ->whereBetween('ideas.fecha_fin', [$fechaInicio, $fechaFin])
            ->sum('puntos');

        $puntosPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) use ($fechaInicio, $fechaFin) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where('ideas.contable', true)
                    ->where('ideas.estatus', 3)
                    ->whereBetween('ideas.fecha_fin', [$fechaInicio, $fechaFin]);
            })
            ->select('areas.nombre as nombre_area', DB::raw('COALESCE(SUM(ideas.puntos), 0) as total_puntos'))
            ->groupBy('areas.id', 'areas.nombre')
            ->orderBy('areas.nombre', 'asc')
            ->get();

        $respuesta = [
            'total_puntos' => $totalPuntos,
            'puntos_por_area' => $puntosPorArea
        ];

        return response()->json(["msg" => $respuesta], 200);
    }

    public function ahorronocontable(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date'
            ]
        );

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        $totalPuntos = DB::table('ideas')
            ->where('contable', false)
            ->where('ideas.estatus', 3)
            ->whereBetween('ideas.fecha_fin', [$fechaInicio, $fechaFin])
            ->sum('puntos');

        $puntosPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) use ($fechaInicio, $fechaFin) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where('ideas.contable', false)
                    ->where('ideas.estatus', 3)
                    ->whereBetween('ideas.fecha_fin', [$fechaInicio, $fechaFin]);
            })
            ->select('areas.nombre as nombre_area', DB::raw('COALESCE(SUM(ideas.puntos), 0) as total_puntos'))
            ->groupBy('areas.id', 'areas.nombre')
            ->orderBy('areas.nombre', 'asc')
            ->get();

        $respuesta = [
            'total_puntos' => $totalPuntos,
            'puntos_por_area' => $puntosPorArea
        ];

        return response()->json(["msg" => $respuesta], 200);
    }

    public function ideasnocontables(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date'
            ]
        );

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        $totalIdeas = DB::table('ideas')
            ->where('contable', false)
            ->where('ideas.estatus', 3)
            ->whereBetween('ideas.fecha_fin', [$fechaInicio, $fechaFin])
            ->count();

        $totalideasPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) use ($fechaInicio, $fechaFin) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where('ideas.contable', false)
                    ->where('ideas.estatus', 3)
                    ->whereBetween('ideas.fecha_fin', [$fechaInicio, $fechaFin]);
            })
            ->select('areas.nombre as nombre_area', DB::raw('COALESCE(COUNT(ideas.id), 0) as total_ideas'))
            ->groupBy('areas.id', 'areas.nombre')
            ->orderBy('areas.nombre', 'asc')
            ->get();

        $respuesta = [
            'total_ideas' => $totalIdeas,
            'ideas_por_area' => $totalideasPorArea
        ];

        return response()->json(["msg" => $respuesta, 200]);
    }

    public function titulo(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'titulo' => 'required|string|max:255'
            ]
        );

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $titulo = $request->get('titulo');
        $ideas = Idea::where('titulo', 'LIKE', "%{$titulo}%")->get();

        return response()->json($ideas, 200);
    }
}
