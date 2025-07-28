<?php

namespace App\Http\Controllers\Ideas;

use App\Http\Controllers\Controller;
use App\Models\Tipo_cambio;
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
                    ->paginate(10);

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
                ->paginate(10);

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

        $ideas = Idea::where('estatus', $estatus)->paginate(10);

        return response()->json(["ideas" => $ideas], 200);
    }

    public function ideasAllCategoria(Request $request, $estatus = null, $categoria)
    {
        $validate = Validator::make(
            [
                'estatus' => $estatus,
                'categoria' => $categoria,
                'area_id' => $request->area_id
            ],
            [
                'estatus' => 'nullable|exists:estado_ideas,id',
                'categoria' => 'required|integer|min:1',
                'area_id' => 'nullable|exists:areas,id'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $query = Idea::query();

        if ($estatus) {
            $query->where('estatus', $estatus);
        }

        if ($categoria == 1) {
            $query->where(function ($q) {
                $q->where('categoria_id', 1)
                    ->orWhereNull('categoria_id');
            });
        } else {
            $query->where('categoria_id', $categoria);
        }

        if ($request->has('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        $ideas = $query->paginate(10);

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
                    'categoria_id' => 'nullable|integer|exists:categorias,id',
                    'antecedentes' => 'nullable| max:2000',
                    'condiciones' => 'required|file|image|mimes:jpeg,png,jpg|max:4900',
                    'propuesta' => 'required|max:2000',
                    'fecha_inicio' => 'required|date',
                    'area_id' => 'required|integer|exists:areas,id',
                    'contable' => 'nullable|boolean',
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
                $idea->categoria_id = $request->filled('categoria_id') ? $request->categoria_id : null;
                $idea->antecedente = $request->filled('antecedentes') ? $request->antecedentes : 'No aplica';
                $idea->propuesta = $request->propuesta;
                $idea->fecha_inicio = $request->fecha_inicio;
                $idea->area_id = $request->area_id;
                $idea->contable = $request->filled('contable') ? $request->contable : 0;
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

    public function asignarBonos(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:ideas,id',
            'usuarios_bonos' => 'required|array',
            'usuarios_bonos.*.usuario_id' => 'required|integer|exists:usuarios,id',
            'usuarios_bonos.*.bono' => 'required|numeric|min:1',
        ]);

        $ideaId = $validated['id'];

        foreach ($validated['usuarios_bonos'] as $item) {
            DB::table('usuarios_bonos')->updateOrInsert(
                [
                    'usuario_id' => $item['usuario_id'],
                    'idea_id' => $ideaId,
                ],
                [
                    'bono' => $item['bono'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        return response()->json([
            'message' => 'Bonos asignados correctamente.',
            'idea_id' => $ideaId,
            'bonos' => $validated['usuarios_bonos'],
        ]);
    }

    public function ideastotales(Request $request)
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
            ->where(function ($query) {
                $query->where('categoria_id', 1)
                    ->orWhereNull('categoria_id');
            })
            ->whereBetween('ideas.fecha_inicio', [$fechaInicio, $fechaFin])
            ->count();

        $totalideasPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) use ($fechaInicio, $fechaFin) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->whereBetween('ideas.fecha_inicio', [$fechaInicio, $fechaFin]);
            })
            ->select('areas.nombre as nombre_area', DB::raw('COALESCE(COUNT(ideas.id), 0) as total_ideas'))
            ->where('areas.is_active', 1)
            ->groupBy('areas.id', 'areas.nombre')
            ->orderBy('areas.nombre', 'asc')
            ->get();

        $respuesta = [
            'total_ideas' => $totalIdeas,
            'ideas_por_area' => $totalideasPorArea
        ];

        return response()->json($respuesta, 200);
    }


    public function ideasTotalesHistoricas(Request $request)
    {

        $totalIdeas = DB::table('ideas')
            ->where(function ($query) {
                $query->where('categoria_id', 1)
                    ->orWhereNull('categoria_id');
            })
            ->count();

        $totalideasPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) {

                $join->on('areas.id', '=', 'ideas.area_id');
            })
            ->select('areas.nombre as nombre_area', DB::raw('COALESCE(COUNT(ideas.id), 0) as total_ideas'))
            ->where('areas.is_active', 1)

            ->groupBy('areas.id', 'areas.nombre')
            ->orderBy('areas.nombre', 'asc')
            ->get();

        $respuesta = [
            'total_ideas' => $totalIdeas,
            'ideas_por_area' => $totalideasPorArea
        ];

        return response()->json($respuesta, 200);
    }

    public function ideasPorEstatusTotalesHistoricas(Request $request)
    {

        $totalIdeas = DB::table('ideas')
            ->count();

        $totalideasPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) {
                $join->on('areas.id', '=', 'ideas.area_id');
            })
            ->select('areas.nombre as nombre_area', DB::raw('COALESCE(COUNT(ideas.id), 0) as total_ideas'))
            ->where('areas.is_active', 1)

            ->groupBy('areas.id', 'areas.nombre')
            ->orderBy('areas.nombre', 'asc')
            ->get();

        $respuesta = [
            'total_ideas' => $totalIdeas,
            'ideas_por_area' => $totalideasPorArea
        ];

        return response()->json($respuesta, 200);
    }

    public function ideasHistoricasEstatusArea(Request $request)
    {
        $totalIdeas = DB::table('ideas')
            ->where(function ($query) {
                $query->where('categoria_id', 1)
                    ->orWhereNull('categoria_id');
            })
            ->count();

        $ideasPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where(function ($query) {
                        $query->where('ideas.categoria_id', 1)
                            ->orWhereNull('ideas.categoria_id');
                    });
            })
            ->select(
                'areas.id as area_id',
                'areas.nombre as nombre_area',
                DB::raw('COUNT(ideas.id) as total_ideas')
            )
            ->where('areas.is_active', 1)
            ->groupBy('areas.id', 'areas.nombre')
            ->orderBy('areas.nombre', 'asc')
            ->get();

        $ideasPorEstatus = DB::table('areas')
            ->leftJoin('ideas', function ($join) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where(function ($query) {
                        $query->where('ideas.categoria_id', 1)
                            ->orWhereNull('ideas.categoria_id');
                    });
            })
            ->leftJoin('estado_ideas', 'ideas.estatus', '=', 'estado_ideas.id')
            ->select(
                'areas.id as area_id',
                'estado_ideas.nombre as nombre_estatus',
                DB::raw('COUNT(ideas.id) as total_por_estatus')
            )
            ->where('areas.is_active', 1)
            ->groupBy('areas.id', 'estado_ideas.id', 'estado_ideas.nombre')
            ->orderBy('estado_ideas.nombre', 'asc')
            ->get();

        $ideasPorAreaConEstatus = $ideasPorArea->map(function ($area) use ($ideasPorEstatus) {
            $estatus = $ideasPorEstatus
                ->where('area_id', $area->area_id)
                ->map(function ($e) {
                    return [
                        'nombre_estatus' => $e->nombre_estatus,
                        'total_por_estatus' => (int) $e->total_por_estatus,
                    ];
                })
                ->values();

            return [
                'area_id' => $area->area_id,
                'nombre_area' => $area->nombre_area,
                'total_ideas' => (int) $area->total_ideas,
                'estatus' => $estatus,
            ];
        });

        return response()->json([
            'total_ideas' => $totalIdeas,
            'ideas_por_area' => $ideasPorAreaConEstatus,
        ], 200);
    }

    public function ideasHistoricasEstatusAreaFiltradas(Request $request)
    {
        // 1. Validación de fechas
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

        // 2. Total general con rango de fechas
        $totalIdeas = DB::table('ideas')
            ->where(function ($q) {
                $q->where('categoria_id', 1)
                    ->orWhereNull('categoria_id');
            })
            ->whereBetween('ideas.fecha_inicio', [$fechaInicio, $fechaFin])
            ->count();

        // 3. Total por área con rango de fechas
        $ideasPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) use ($fechaInicio, $fechaFin) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where(function ($q) {
                        $q->where('ideas.categoria_id', 1)
                            ->orWhereNull('ideas.categoria_id');
                    })
                    ->whereBetween('ideas.fecha_inicio', [$fechaInicio, $fechaFin]);
            })
            ->select(
                'areas.id as area_id',
                'areas.nombre as nombre_area',
                DB::raw('COUNT(ideas.id) as total_ideas')
            )
            ->where('areas.is_active', 1)
            ->groupBy('areas.id', 'areas.nombre')
            ->orderBy('areas.nombre', 'asc')
            ->get();

        // 4. Total por área y estatus con rango de fechas
        $ideasPorEstatus = DB::table('areas')
            ->leftJoin('ideas', function ($join) use ($fechaInicio, $fechaFin) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where(function ($q) {
                        $q->where('ideas.categoria_id', 1)
                            ->orWhereNull('ideas.categoria_id');
                    })
                    ->whereBetween('ideas.fecha_inicio', [$fechaInicio, $fechaFin]);
            })
            ->leftJoin('estado_ideas', 'ideas.estatus', '=', 'estado_ideas.id')
            ->select(
                'areas.id as area_id',
                'estado_ideas.nombre as nombre_estatus',
                DB::raw('COUNT(ideas.id) as total_por_estatus')
            )
            ->where('areas.is_active', 1)
            ->groupBy('areas.id', 'estado_ideas.id', 'estado_ideas.nombre')
            ->orderBy('estado_ideas.nombre', 'asc')
            ->get();

        // 5. Armar la respuesta anidada
        $ideasPorAreaConEstatus = $ideasPorArea->map(function ($area) use ($ideasPorEstatus) {
            $estatus = $ideasPorEstatus
                ->where('area_id', $area->area_id)
                ->map(fn($e) => [
                    'nombre_estatus' => $e->nombre_estatus,
                    'total_por_estatus' => (int) $e->total_por_estatus,
                ])
                ->values();

            return [
                'area_id' => $area->area_id,
                'nombre_area' => $area->nombre_area,
                'total_ideas' => (int) $area->total_ideas,
                'estatus' => $estatus,
            ];
        });

        // 6. Devolvemos JSON
        return response()->json([
            'total_ideas' => $totalIdeas,
            'ideas_por_area' => $ideasPorAreaConEstatus,
        ], 200);
    }


    public function ideasHistoricasEstatusCategoria()
    {
        $totalIdeas = DB::table('ideas')
            ->select(DB::raw('COUNT(*)'))
            ->whereIn('categoria_id', function ($query) {
                $query->select('id')->from('categorias');
            })
            ->orWhereNull('categoria_id')
            ->count();

        $categorias = DB::table('categorias')
            ->select('id', 'nombre')
            ->get();

        $ideasPorCategoria = DB::table('ideas')
            ->selectRaw("
            IFNULL(categoria_id, 1) as categoria_id_normalizada,
            COUNT(*) as total_ideas
        ")
            ->groupBy('categoria_id_normalizada')
            ->get()
            ->keyBy('categoria_id_normalizada');

        $ideasPorEstatus = DB::table('ideas')
            ->leftJoin('estado_ideas', 'ideas.estatus', '=', 'estado_ideas.id')
            ->selectRaw("
            IFNULL(ideas.categoria_id, 1) as categoria_id_normalizada,
            estado_ideas.nombre as nombre_estatus,
            COUNT(ideas.id) as total_por_estatus
        ")
            ->groupBy('categoria_id_normalizada', 'estado_ideas.nombre')
            ->get()
            ->groupBy('categoria_id_normalizada');

        $respuesta = $categorias->map(function ($cat) use ($ideasPorCategoria, $ideasPorEstatus) {
            $categoriaId = $cat->id;
            $totalIdeas = $ideasPorCategoria[$categoriaId]->total_ideas ?? 0;

            $estatus = collect($ideasPorEstatus->get($categoriaId, []))->map(function ($e) {
                return [
                    'nombre_estatus' => $e->nombre_estatus,
                    'total_por_estatus' => (int) $e->total_por_estatus,
                ];
            })->values();

            return [
                'categoria_id' => $categoriaId,
                'nombre_categoria' => $cat->nombre,
                'total_ideas' => (int) $totalIdeas,
                'estatus' => $estatus,
            ];
        });

        return response()->json([
            'total_ideas' => $totalIdeas,
            'ideas_por_categoria' => $respuesta
        ]);
    }

    public function ideasHistoricasEstatusCategoriaFiltradas(Request $request)
    {
        // Validar las fechas
        $validate = Validator::make($request->all(), [
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        // 1. Total general de ideas (incluye las con categoria_id = null tratadas como 1)
        $totalIdeas = DB::table('ideas')
            ->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
            ->where(function ($q) {
                $q->whereIn('categoria_id', function ($query) {
                    $query->select('id')->from('categorias');
                })->orWhereNull('categoria_id');
            })
            ->count();

        // 2. Obtener todas las categorías activas
        $categorias = DB::table('categorias')
            ->select('id', 'nombre')
            ->get();

        // 3. Total por categoría (agrupando NULL como categoria_id = 1)
        $ideasPorCategoria = DB::table('ideas')
            ->selectRaw("IFNULL(categoria_id, 1) AS categoria_id_normalizada")
            ->selectRaw("COUNT(*) as total_ideas")
            ->whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->groupBy('categoria_id_normalizada')
            ->get()
            ->keyBy('categoria_id_normalizada');

        // 4. Ideas por estatus por categoría (también agrupando NULL como 1)
        $ideasPorEstatus = DB::table('ideas')
            ->leftJoin('estado_ideas', 'ideas.estatus', '=', 'estado_ideas.id')
            ->selectRaw("IFNULL(ideas.categoria_id, 1) AS categoria_id_normalizada")
            ->selectRaw("estado_ideas.nombre AS nombre_estatus")
            ->selectRaw("COUNT(ideas.id) AS total_por_estatus")
            ->whereBetween('ideas.fecha_inicio', [$fechaInicio, $fechaFin])
            ->groupBy('categoria_id_normalizada', 'estado_ideas.nombre')
            ->get()
            ->groupBy('categoria_id_normalizada');

        // 5. Armar respuesta
        $respuesta = $categorias->map(function ($cat) use ($ideasPorCategoria, $ideasPorEstatus) {
            $categoriaId = $cat->id;
            $totalIdeas = $ideasPorCategoria[$categoriaId]->total_ideas ?? 0;

            $estatus = collect($ideasPorEstatus->get($categoriaId, []))->map(function ($e) {
                return [
                    'nombre_estatus' => $e->nombre_estatus,
                    'total_por_estatus' => (int) $e->total_por_estatus,
                ];
            })->values();

            return [
                'categoria_id' => $categoriaId,
                'nombre_categoria' => $cat->nombre,
                'total_ideas' => (int) $totalIdeas,
                'estatus' => $estatus,
            ];
        });

        return response()->json([
            'total_ideas' => $totalIdeas,
            'ideas_por_categoria' => $respuesta
        ]);
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
            ->where(function ($query) {
                $query->where('categoria_id', 1)
                    ->orWhereNull('categoria_id');
            })
            ->where('contable', true)
            ->where('ideas.estatus', 3)
            ->whereBetween('ideas.fecha_inicio', [$fechaInicio, $fechaFin])
            ->count();

        $totalideasPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) use ($fechaInicio, $fechaFin) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where('ideas.contable', true)
                    ->where('ideas.estatus', 3)
                    ->where('ideas.fecha_inicio', '>=', $fechaInicio);
            })
            ->select('areas.nombre as nombre_area', DB::raw('COALESCE(COUNT(ideas.id), 0) as total_ideas'))
            ->where('areas.is_active', 1)
            ->groupBy('areas.id', 'areas.nombre')
            ->orderBy('areas.nombre', 'asc')
            ->get();

        $respuesta = [
            'total_ideas' => $totalIdeas,
            'ideas_por_area' => $totalideasPorArea
        ];

        return response()->json($respuesta, 200);
    }

    public function ideasContablesHistoricas(Request $request)
    {

        $totalIdeas = DB::table('ideas')
            ->where('contable', true)
            ->where('ideas.estatus', 3)
            ->where(function ($query) {
                $query->where('categoria_id', 1)
                    ->orWhereNull('categoria_id');
            })
            ->count();

        $totalideasPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where('ideas.contable', true)
                    ->where('ideas.estatus', 3);
            })
            ->select('areas.nombre as nombre_area', DB::raw('COALESCE(COUNT(ideas.id), 0) as total_ideas'))
            ->where('areas.is_active', 1)
            ->groupBy('areas.id', 'areas.nombre')
            ->orderBy('areas.nombre', 'asc')
            ->get();

        $respuesta = [
            'total_ideas' => $totalIdeas,
            'ideas_por_area' => $totalideasPorArea
        ];

        return response()->json($respuesta, 200);
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
            ->whereBetween('ideas.fecha_inicio', [$fechaInicio, $fechaFin])
            ->count();

        $totalideasPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) use ($fechaInicio) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where('ideas.contable', false)
                    ->where('ideas.estatus', 3)
                    ->where('ideas.fecha_inicio', '>=', $fechaInicio);
            })
            ->select('areas.nombre as nombre_area', DB::raw('COALESCE(COUNT(ideas.id), 0) as total_ideas'))
            ->where('areas.is_active', 1)
            ->groupBy('areas.id', 'areas.nombre')
            ->orderBy('areas.nombre', 'asc')
            ->get();



        $respuesta = [
            'total_ideas' => $totalIdeas,
            'ideas_por_area' => $totalideasPorArea
        ];

        return response()->json(["msg" => $respuesta, 200]);
    }

    public function ideasNoContablesHistoricas(Request $request)
    {
        $totalIdeas = DB::table('ideas')
            ->where('contable', false)
            ->where('ideas.estatus', 3)
            ->where(function ($query) {
                $query->where('categoria_id', 1)
                    ->orWhereNull('categoria_id');
            })
            ->count();

        $totalideasPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where('ideas.contable', false)
                    ->where('ideas.estatus', 3);
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


    public function ahorrocontable(Request $request)
    {

        $tipoCambio = Tipo_Cambio::where('moneda_origen', 'USD')->value('valor');

        if (!$tipoCambio || $tipoCambio <= 0) {
            return response()->json(['error' => 'Tipo de cambio no válido o no encontrado.'], 400);
        }

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
            ->whereBetween('ideas.fecha_inicio', [$fechaInicio, $fechaFin])
            ->sum('ahorro');

        $totalAhorrosDolares = round($totalAhorros / $tipoCambio, 2);

        $ahorrosPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) use ($fechaInicio, $fechaFin) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where('ideas.contable', true)
                    ->where('ideas.estatus', 3)
                    ->whereBetween('ideas.fecha_inicio', [$fechaInicio, $fechaFin])
                    ->where(function ($q) {
                        $q->where('ideas.categoria_id', 1)
                            ->orWhereNull('ideas.categoria_id');
                    });
            })
            ->select(
                'areas.nombre as nombre_area',
                DB::raw('COALESCE(SUM(ideas.ahorro), 0) as total_ahorros'),
                DB::raw('ROUND(COALESCE(SUM(ideas.ahorro), 0) / ' . $tipoCambio . ', 2) as total_ahorros_dolares')
            )
            ->groupBy('ideas.area_id', 'areas.nombre')
            ->orderBy('areas.nombre', 'asc')
            ->get();

        $respuesta = [
            'total_ahorros' => $totalAhorros,
            'total_ahorros_usd' => $totalAhorrosDolares,
            'ahorros_por_area' => $ahorrosPorArea,
        ];

        return response()->json(["msg" => $respuesta], 200);
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
            ->where('contable', 1)
            ->where('ideas.estatus', 3)
            ->whereDate('ideas.fecha_inicio', '>=', $fechaInicio)
            ->sum('puntos');

        $puntosPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) use ($fechaInicio) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where('ideas.contable', true)
                    ->where('ideas.estatus', 3)
                    ->whereDate('ideas.fecha_inicio', '>=', $fechaInicio);
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

    public function puntosnocontables(Request $request)
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
            ->where('ideas.fecha_fin', '>=', $fechaInicio)
            ->sum('puntos');

        $puntosPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) use ($fechaInicio, $fechaFin) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where('ideas.contable', false)
                    ->where('ideas.estatus', 3)
                    ->where('ideas.fecha_fin', '>=', $fechaInicio);
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

    public function top10Usuarios()
    {
        $historial = DB::table('usuarios_periodos')
            ->join('usuarios', 'usuarios_periodos.user_id', '=', 'usuarios.id')
            ->select('usuarios_periodos.user_id', 'usuarios.ibm', 'usuarios.nombre', DB::raw('SUM(usuarios_periodos.puntos) as total_puntos'))
            ->where('usuarios_periodos.is_active', true)
            ->where('usuarios.is_active', true)
            //->whereBetween('usuarios_periodos.fecha', [$fechaInicio, $fechaFin])
            ->groupBy('usuarios_periodos.user_id', 'usuarios.ibm', 'usuarios.nombre')
            ->orderBy('total_puntos', 'desc')
            ->limit(10)
            ->get();
        return response()->json(["historial" => $historial], 200);
    }

    public function puntosContablesHistoricos()
    {
        $totalPuntos = DB::table('ideas')
            ->where('contable', true)
            ->where('ideas.estatus', 3)
            ->sum('puntos');

        $puntosPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where('ideas.contable', true)
                    ->where('ideas.estatus', 3);

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


    public function puntosNoContablesHistoricos()
    {
        $totalPuntos = DB::table('ideas')
            ->where('contable', false)
            ->where('ideas.estatus', 3)
            ->sum('puntos');

        $puntosPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where('ideas.contable', false)
                    ->where('ideas.estatus', 3);
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

    public function ahorroHistorico()
    {
        $tipoCambio = Tipo_Cambio::where('moneda_origen', 'USD')->value('valor');

        if (!$tipoCambio || $tipoCambio <= 0) {
            return response()->json(['error' => 'Tipo de cambio no válido o no encontrado.'], 400);
        }

        $totalAhorros = DB::table('ideas')
            ->where('contable', true)
            ->where('estatus', 3)
            ->where(function ($query) {
                $query->where('categoria_id', 1)
                    ->orWhereNull('categoria_id');
            })
            ->sum('ahorro');

        $totalAhorrosPesos = round($totalAhorros, 2);
        $totalAhorrosDolares = round($totalAhorros / $tipoCambio, 2);

        $ahorrosPorArea = DB::table('areas')
            ->leftJoin('ideas', function ($join) {
                $join->on('areas.id', '=', 'ideas.area_id')
                    ->where('ideas.contable', true)
                    ->where('ideas.estatus', 3)
                    ->where(function ($q) {
                        $q->where('ideas.categoria_id', 1)
                            ->orWhereNull('ideas.categoria_id');
                    });
            })
            ->select(
                'areas.nombre as nombre_area',
                DB::raw('ROUND(COALESCE(SUM(ideas.ahorro), 0), 2) as total_ahorros')
            )
            ->groupBy('ideas.area_id', 'areas.nombre')
            ->orderBy('areas.nombre', 'asc')
            ->get();

        $ahorrosPorArea = $ahorrosPorArea->map(function ($item) use ($tipoCambio) {
            $item->total_ahorros_dolares = round($item->total_ahorros / $tipoCambio, 2);
            return $item;
        });

        $respuesta = [
            'total_ahorros' => $totalAhorrosPesos,
            'total_ahorros_usd' => $totalAhorrosDolares,
            'ahorros_por_area' => $ahorrosPorArea,
        ];

        return response()->json(["msg" => $respuesta], 200);
    }

    public function ahorrosHistoricosPorCategoria()
    {
        $tipoCambio = Tipo_Cambio::where('moneda_origen', 'USD')->value('valor');

        if (!$tipoCambio || $tipoCambio <= 0) {
            return response()->json(['error' => 'Tipo de cambio no válido o no encontrado.'], 400);
        }

        $ahorrosPorCategoria = DB::table('ideas')
            ->leftJoin('categorias', 'ideas.categoria_id', '=', 'categorias.id')
            ->where('ideas.contable', true)
            ->where('ideas.estatus', 3)
            ->selectRaw("
            CASE 
                WHEN ideas.categoria_id = 1 OR ideas.categoria_id IS NULL THEN 'Ideas' 
                ELSE categorias.nombre 
            END AS nombre_categoria,
            ROUND(SUM(ideas.ahorro), 2) AS total_ahorros
        ")
            ->groupBy('nombre_categoria')
            ->orderBy('nombre_categoria', 'asc')
            ->get();

        $totalPesos = $ahorrosPorCategoria->sum('total_ahorros');
        $totalDolares = round($totalPesos / $tipoCambio, 2);

        $ahorrosPorCategoria = $ahorrosPorCategoria->map(function ($item) use ($tipoCambio) {
            $item->total_ahorros_dolares = round($item->total_ahorros / $tipoCambio, 2);
            return $item;
        });

        $respuesta = [
            'total_ahorros' => round($totalPesos, 2),
            'total_ahorros_usd' => $totalDolares,
            'ahorros_por_categoria' => $ahorrosPorCategoria,
        ];

        return response()->json(["msg" => $respuesta], 200);
    }

    public function ahorrosHistoricosPorCategoriaFechas(Request $request)
    {
        $tipoCambio = Tipo_Cambio::where('moneda_origen', 'USD')->value('valor');

        if (!$tipoCambio || $tipoCambio <= 0) {
            return response()->json(['error' => 'Tipo de cambio no válido o no encontrado.'], 400);
        }

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

        $ahorrosPorCategoria = DB::table('ideas')
            ->leftJoin('categorias', 'ideas.categoria_id', '=', 'categorias.id')
            ->where('ideas.contable', true)
            ->where('ideas.estatus', 3)
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('ideas.created_at', [$fechaInicio, $fechaFin]);
            })
            ->selectRaw("
            CASE 
                WHEN ideas.categoria_id = 1 OR ideas.categoria_id IS NULL THEN 'Ideas' 
                ELSE categorias.nombre 
            END AS nombre_categoria,
            ROUND(SUM(ideas.ahorro), 2) AS total_ahorros
        ")
            ->groupBy('nombre_categoria')
            ->orderBy('nombre_categoria', 'asc')
            ->get();

        $totalPesos = $ahorrosPorCategoria->sum('total_ahorros');
        $totalDolares = round($totalPesos / $tipoCambio, 2);

        $ahorrosPorCategoria = $ahorrosPorCategoria->map(function ($item) use ($tipoCambio) {
            $item->total_ahorros_dolares = round($item->total_ahorros / $tipoCambio, 2);
            return $item;
        });

        $respuesta = [
            'total_ahorros' => round($totalPesos, 2),
            'total_ahorros_usd' => $totalDolares,
            'ahorros_por_categoria' => $ahorrosPorCategoria,
        ];

        return response()->json(["msg" => $respuesta], 200);
    }

    public function reporteIdeasVsUsuarios(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        $ideasQuery = DB::table('ideas');

        if ($fechaInicio && $fechaFin) {
            $ideasQuery->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin]);
        }

        $totalIdeas = $ideasQuery->count();

        $totalUsuarios = DB::table('usuarios')->count();

        $porcentaje = $totalUsuarios > 0
            ? round(($totalIdeas / $totalUsuarios) * 100, 2)
            : 0;

        return response()->json([
            'total_ideas' => $totalIdeas,
            'total_usuarios' => $totalUsuarios,
            'porcentaje_por_usuario' => $porcentaje,
        ]);
    }




    public function reporteParticipacionEmpleados(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $f0 = $request->fecha_inicio;
        $f1 = $request->fecha_fin;

        $ideasQuery = DB::table('ideas');
        $colabQuery = DB::table('ideas as i')
            ->join('equipos as e', 'e.id_idea', '=', 'i.id')
            ->join('usuarios_equipos as ue', 'ue.id_equipo', '=', 'e.id');

        if ($f0 && $f1) {
            $ideasQuery->whereBetween('created_at', [$f0, $f1]);
            $colabQuery->whereBetween('i.created_at', [$f0, $f1]);
        }

        $totalIdeas = $ideasQuery->count();

        $totalColaboradores = $colabQuery->distinct('ue.id_usuario')->count('ue.id_usuario');

        $totalEmpleados = DB::table('usuarios')->count();

        $porcentajeParticipacion = $totalEmpleados > 0
            ? round(($totalColaboradores / $totalEmpleados) * 100, 2)
            : 0;

        return response()->json([
            'total_ideas' => $totalIdeas,
            'total_colaboradores' => $totalColaboradores,
            'total_empleados' => $totalEmpleados,
            'porcentaje_participacion' => $porcentajeParticipacion,
        ]);
    }




    public function actualizarTipoCambio(Request $request)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0.0001',
        ]);

        $tipoCambio = Tipo_Cambio::where('moneda_origen', 'USD')->first();

        if (!$tipoCambio) {
            $tipoCambio = Tipo_Cambio::create([
                'moneda_origen' => 'USD',
                'valor' => $request->valor
            ]);
        } else {
            $tipoCambio->update(['valor' => $request->valor]);
        }

        return response()->json(['message' => 'Tipo de cambio actualizado correctamente.', 'tipo_cambio' => $tipoCambio], 200);
    }

    public function obtenerTipoCambio()
    {
        $tipoCambio = Tipo_Cambio::where('moneda_origen', 'USD')->value('valor');

        if (!$tipoCambio || $tipoCambio <= 0) {
            return response()->json(['error' => 'Tipo de cambio no encontrado o inválido.'], 404);
        }

        return response()->json([
            'moneda_origen' => 'USD',
            'valor' => $tipoCambio
        ], 200);
    }

}
