<?php

namespace App\Http\Controllers\Actividades;

use App\Http\Controllers\Controller;
use App\Models\Estado_Actividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EstadoActividadesController extends Controller
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
        $estados = Estado_Actividad::all();
        return response()->json(["estados" => $estados], 200);
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
                'nombre' => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $estado = new Estado_Actividad();
        $estado->nombre = $request->nombre;
        $estado->save();

        return response()->json(["msg" => "Estado de Actividad creado correctamente"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $estado = Estado_Actividad::find($id);
        if ($estado) {
            return response()->json(["estado" => $estado], 200);
        }
        return response()->json(["msg" => "Estado de Actividad no encontrado"], 404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estado_Actividad $estado_Actividad)
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
                'id' => 'required|integer|exists:estado_actividades,id',
                'nombre' => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
                'is_active' => 'required|boolean',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $estado = Estado_Actividad::find($request->id);
        if (!$estado) {
            return response()->json(["msg" => "Estado de Actividad no encontrado"], 404);
        }

        $estado->nombre = $request->nombre;
        $estado->is_active = $request->is_active;
        $estado->save();
        return response()->json(["msg" => "Estado de Actividad actualizado correctamente"], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $estado = Estado_Actividad::find($id);
        if ($estado) {
            $estado->is_active = false;
            $estado->save();
            return response()->json(["msg" => "Estado de Actividad eliminado correctamente"], 200);
        }
        return response()->json(["msg" => "Estado de Actividad no encontrado"], 404);
    }
}
