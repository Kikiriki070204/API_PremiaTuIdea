<?php

namespace App\Http\Controllers\Equipo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Usuario_Equipo;
use Illuminate\Support\Facades\Validator;

class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipos = Equipo::all();
        return response()->json(["equipos" => $equipos], 200);
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
                'id_idea' => 'required|integer|exists:ideas,id',
                'nombre' => 'required|max:255'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $equipo = new Equipo();
        $equipo->id_idea = $request->id_idea;
        $equipo->nombre = $request->nombre;
        $equipo->save();

        $userTeam = new Usuario_Equipo();
        $userTeam->id_usuario = auth()->user()->id;
        $userTeam->id_equipo = $equipo->id;
        $userTeam->save();

        return response()->json([
            "msg" => "Equipo creado correctamente"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $equipo = Equipo::find($id);
        if ($equipo) {
            return response()->json(["equipo" => $equipo], 200);
        }
        return response()->json(["msg" => "Equipo no encontrado"], 404);
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
                'id' => 'required|integer|exists:equipos,id',
                'nombre' => 'required|max:255',
                'is_active' => 'required|boolean',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $equipo = Equipo::find($request->id);
        $equipo->nombre = $request->nombre;
        $equipo->is_active = $request->is_active;
        $equipo->save();
        return response()->json([
            "msg" => "Equipo actualizado correctamente"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $equipo = Equipo::find($id);
        if ($equipo) {
            $equipo->is_active = false;
            $equipo->save();
            return response()->json([
                "msg" => "Equipo eliminado correctamente"
            ], 200);
        }
        return response()->json(["msg" => "Equipo no encontrado"], 404);
    }
}
