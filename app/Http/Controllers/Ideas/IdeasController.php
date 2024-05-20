<?php

namespace App\Http\Controllers\Ideas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Idea;
use Illuminate\Support\Facades\Validator;
use App\Models\Equipo;
use App\Models\Usuario_Equipo;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\aceptacion;
use App\Mail\rechazo;

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
            $ideas = Idea::where('user_id', $user->id)->where('estatus', 3)->get();

            return response()->json(["ideas" => $ideas], 200);
        }
        return response()->json(["msg" => "Usuario no Encontrado"], 401);
    }

    public function userIdeasAll(Request $request)
    {
        $user = auth('api')->user();

        if ($user) {
            $validate = Validator::make(
                $request->all(),
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


            if ($request->estatus == null) {
                $ideas = Idea::where('user_id', $user->id)->get();

                return response()->json(["ideas" => $ideas, "estatus"], 200);
            }

            $ideas = Idea::where('user_id', $user->id)->where('estatus', $request->estatus)->get();

            return response()->json(["ideas" => $ideas], 200);
        }
        return response()->json(["msg" => "Usuario no Encontrado"], 401);
    }

    public function ideasAll(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
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

        if ($request->estatus == null) {
            $ideas = Idea::all();

            return response()->json(["ideas" => $ideas], 200);
        }

        $ideas = Idea::where('estatus', $request->estatus)->get();

        return response()->json(["ideas" => $ideas], 200);
    }

    public function create(Request $request)
    {
        $user = auth('api')->user();

        if ($user) {
            $validate = Validator::make(
                $request->all(),
                [
                    'titulo' => 'required|min:5',
                    'antecedentes' => 'required| max: 2000',
                    //'condicion_actual'
                    'propuesta' => 'required|max: 2000',
                    //equipo_id
                ]
            );

            if ($validate->fails()) {
                return response()->json([
                    "errors" => $validate->errors(),
                    "msg" => "Errores de validación"
                ], 422);
            }

            $idea = new Idea();

            $idea->user_id = $user->id;
            $idea->titulo = $request->titulo;
            $idea->antecedente = $request->antecedentes;
            $idea->propuesta = $request->propuesta;
            $idea->save();

            $Equipo = new Equipo();
            $Equipo->id_idea = $idea->id;
            $Equipo->save();

            $userTeam = new Usuario_Equipo();
            $userTeam->id_usuario = $user->id;
            $userTeam->id_equipo = $Equipo->id;
            $userTeam->save();

            return $idea;
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
        $colaboradores = DB::table('usuarios_equipos')
            ->join('equipos', 'usuarios_equipos.id_equipo', '=', 'equipos.id')
            ->join('usuarios', 'usuarios_equipos.id_usuario', '=', 'usuarios.id')
            ->join('ideas', 'equipos.id_idea', '=', 'ideas.id')
            ->select('usuarios.nombre', 'usuarios.id')
            ->where('ideas.id', $idea->id)
            ->get();

        if ($idea) {
            return response()->json(["idea" => $idea, "colaboradores" => $colaboradores], 200);
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
                'estatus' => 'required|integer|exists:estado_ideas,id'
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
        $idea->save();

        if ($request->estatus == 2) {
            $equipo = Equipo::where('id_idea', $idea->id)->first();
            $users = Usuario_Equipo::where('id_equipo', $equipo->id)->get();
            foreach ($users as $user) {
                $usuario = Usuario::where('id', $user->id_usuario)->first();
                if ($usuario->email == null) {
                    //return response()->json(["msg" => "Usuario sin correo"], 404);
                } else {
                    Mail::to($usuario->email)->send(new aceptacion($usuario));
                }
            }
        } elseif ($request->estatus == 4) {
            $equipo = Equipo::where('id_idea', $idea->id)->first();
            $users = Usuario_Equipo::where('id_equipo', $equipo->id)->get();
            foreach ($users as $user) {
                $usuario = Usuario::where('id', $user->id_usuario)->first();
                if ($usuario->email == null) {
                    //return response()->json(["msg" => "Usuario sin correo"], 404);
                } else {
                    Mail::to($usuario->email)->send(new rechazo($usuario));
                }
            }
        }

        return response()->json(["msg" => "Idea actualizada correctamente"], 200);
    }

    public function destroy($id)
    {
        $idea = Idea::where('id', $id)->first();

        if ($idea) {
            $idea->estatus = 4;
            $idea->save();
            $equipo = Equipo::where('id_idea', $idea->id)->first();
            $users = Usuario_Equipo::where('id_equipo', $equipo->id)->get();
            foreach ($users as $user) {
                $usuario = Usuario::where('id', $user->id_usuario)->first();
                if ($usuario->email == null) {
                    //return response()->json(["msg" => "Usuario sin correo"], 404);
                } else {
                    Mail::to($usuario->email)->send(new rechazo($usuario));
                }
            }
            return response()->json(["msg" => "Idea eliminada correctamente"], 200);
        }
        return response()->json(["msg" => "Idea no encontrada"], 404);
    }

    public function puntos(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'id' => 'required|integer|exists:ideas,id',
                'puntos' => 'required|integer'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $equipo = Equipo::where('id_idea', $request->id)->first();
        $users = Usuario_Equipo::where('id_equipo', $equipo->id)->get();

        foreach ($users as $user) {
            $usuario = Usuario::where('id', $user->id_usuario)->first();
            $usuario->puntos += $request->puntos;
            $usuario->save();
        }

        return response()->json(["msg" => "Puntos asignados correctamente"], 200);
    }
}
