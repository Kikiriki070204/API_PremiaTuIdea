<?php

namespace App\Http\Controllers\Equipo;

use App\Http\Controllers\Controller;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Usuario_Equipo;

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
                'id_usuario' => 'required|integer|exists:usuarios,id',
                'id_equipo' => 'required|integer|exists:equipos,id',
                'nombre' => 'max:255|nullable|regex:/^[a-zA-Z0-9\s]+$/u',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $usuarioEquipo = new Usuario_Equipo();
        $usuarioEquipo->id_usuario = $request->id_usuario;
        $usuarioEquipo->id_equipo = $request->id_equipo;
        $usuarioEquipo->save();
        if ($request->nombre) {
            $equipo = Equipo::find($request->id_equipo);
            $equipo->nombre = $request->nombre;
            $equipo->save();
        }
        return response()->json([
            "msg" => "Usuario asignado al equipo correctamente"
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
                'id' => 'required|integer|exists:usuario_equipo,id',
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
    public function destroy($id)
    {
        $usuarioEquipo = Usuario_Equipo::find($id);
        if ($usuarioEquipo) {
            $usuarioEquipo->is_active = false;
            return response()->json([
                "msg" => "Usuario eliminado del equipo correctamente"
            ], 200);
        }
        return response()->json([
            "msg" => "Usuario no encontrado"
        ], 404);
    }
}
