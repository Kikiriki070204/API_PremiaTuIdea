<?php

namespace App\Http\Controllers\Actividades;

use App\Http\Controllers\Controller;
use App\Models\Actividades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActividadesController extends Controller
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
        $actividades = Actividades::all();
        return response()->json(["actividades" => $actividades], 200);
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
                'responsable' => 'required|integer|exists:usuarios,id',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $actividad = new Actividades();
        $actividad->id_idea = $request->id_idea;
        $actividad->responsable = $request->responsable;
        $actividad->save();
        return response()->json([
            "msg" => "Actividad creada correctamente"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $actividad = Actividades::where('id', $id)->first();

        if ($actividad) {
            return response()->json(["actividad" => $actividad], 200);
        }
        return response()->json(["msg" => "Actividad no encontrada"], 404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Actividades $actividades)
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
                'id' => 'required|integer|exists:actividades,id',
                'id_idea' => 'required|integer|exists:ideas,id',
                'responsable' => 'required|integer|exists:usuarios,id',
                'fecha_inicio' => 'required|date',
                'fecha_finalizacion' => 'nullable|date',
                'id_estado_actividad' => 'required|integer|exists:estado_actividades,id'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $actividad = Actividades::where('id', $request->id)->first();

        if (!$actividad) {
            return response()->json(["msg" => "Actividad no encontrada"], 404);
        }

        $actividad->id_idea = $request->id_idea;
        $actividad->responsable = $request->responsable;
        $actividad->fecha_inicio = $request->fecha_inicio;
        $actividad->fecha_finalizacion = $request->fecha_finalizacion;
        $actividad->id_estado_actividad = $request->id_estado_actividad;
        $actividad->save();
        return response()->json([
            "msg" => "Actividad actualizada correctamente"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $actividad = Actividades::where('id', $id)->first();

        if ($actividad) {
            $actividad->id_estado_actividad = 3;
            $actividad->save();
            return response()->json(["msg" => "Actividad eliminada correctamente"], 200);
        }
        return response()->json(["msg" => "Actividad no encontrada"], 404);
    }
}
