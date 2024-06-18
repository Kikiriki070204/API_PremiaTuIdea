<?php

namespace App\Http\Controllers\Equipo;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Usuario_Equipo;

use function PHPSTORM_META\map;

class UsuarioEquipoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuariosEquipos = Usuario_Equipo::all();
        return response()->json(["usuariosEquipos" => $usuariosEquipos], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'id_usuarios' => 'required|array',
                'id_usuarios.*' => 'integer|exists:usuarios,id',
                'id' => 'required|integer|exists:ideas,id',
                'nombre' => 'max:255|nullable|regex:/^[a-zA-Z0-9\s]+$/u',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $id_equipo = Equipo::where('id_idea', $request->id)->first();
        if (!$id_equipo) {
            $equipo = new Equipo();
            $equipo->id_idea = $request->id;
            $equipo->nombre = $request->nombre;
            $equipo->save();
            $id_equipo = $equipo;
        }

        // Obtener el id_user de la idea
        $ideaOwnerId = Idea::where('id', $request->id)->value('user_id');

        foreach ($request->id_usuarios as $id_usuario) {
            $usuarioEquipo = Usuario_Equipo::firstOrNew([
                'id_usuario' => $id_usuario,
                'id_equipo' => $id_equipo->id
            ]);

            if (!$usuarioEquipo->exists || $usuarioEquipo->is_active == 0) {
                $usuarioEquipo->is_active = true;
                $usuarioEquipo->save();
            }
        }

        $usuariosActuales = Usuario_Equipo::where('id_equipo', $id_equipo->id)->get();

        foreach ($usuariosActuales as $usuarioActual) {
            if (!in_array($usuarioActual->id_usuario, $request->id_usuarios) && $usuarioActual->id_usuario != $ideaOwnerId) {
                $usuarioActual->is_active = false;
                $usuarioActual->save();
            }
        }

        return response()->json([
            "msg" => "Usuarios actualizados correctamente en el equipo"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $userTeam = Usuario_Equipo::find($id);
        return response()->json(["userTeam" => $userTeam], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'id' => 'required|integer|exists:usuarios_equipos,id',
                'id_usuario' => 'required|integer|exists:usuarios,id',
                'id_equipo' => 'required|integer|exists:equipos,id',
                'is_active' => 'required|boolean',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $usuarioEquipo = Usuario_Equipo::find($request->id);
        $usuarioEquipo->id_usuario = $request->id_usuario;
        $usuarioEquipo->id_equipo = $request->id_equipo;
        $usuarioEquipo->is_active = $request->is_active;
        $usuarioEquipo->save();
        return response()->json([
            "msg" => "Usuario asignado al equipo correctamente"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:ideas,id',
            'id_usuarios' => 'required|array',
            'id_usuarios.*' => 'integer|exists:usuarios,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "errors" => $validator->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $equipo = Equipo::where('id', $request->id_idea)->first();

        if (!$equipo) {
            return response()->json([
                "msg" => "No se encontro el equipo"
            ], 404);
        }

        foreach ($request->id_usuarios as $id_usuario) {
            $usuarioEquipo = Usuario_Equipo::where('id_usuario', $id_usuario)->where('id_equipo', $equipo->id)->first();
            $usuarioEquipo->is_active = false;
            $usuarioEquipo->save();
        }

        return response()->json([
            "msg" => "Usuario eliminado del equipo correctamente"
        ], 200);
    }
}
