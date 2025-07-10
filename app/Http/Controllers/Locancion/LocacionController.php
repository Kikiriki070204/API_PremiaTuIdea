<?php

namespace App\Http\Controllers\Locancion;

use App\Http\Controllers\Controller;
use App\Models\Locacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocacionController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locaciones = Locacion::all();
        return response()->json(["locaciones" => $locaciones], 200);
    }

    public function area(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'area_id' => 'required|integer|exists:areas,id',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $locaciones = Locacion::where('area_id', $request->area_id)->get();
        return response()->json(["locaciones" => $locaciones], 200);
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
                'nombre' => 'required|string|max:255',
                'area_id' => 'required|integer|exists:areas,id',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $locacion = new Locacion();
        $locacion->nombre = $request->nombre;
        $locacion->area_id = $request->area_id;
        $locacion->save();

        return response()->json(["msg" => "Locación creada correctamente"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $locacion = Locacion::find($id);
        if ($locacion) {
            return response()->json(["locacion" => $locacion], 200);
        } else {
            return response()->json(["msg" => "Locación no encontrada"], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Locacion $locacion)
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
                'nombre' => 'required|string|max:255',
                'area_id' => 'required|integer|exists:areas,id',
                'id' => 'required|integer|exists:locaciones,id',
                'is_active' => 'required|boolean',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $locacion = Locacion::find($request->id);
        if ($locacion) {
            $locacion->nombre = $request->nombre;
            $locacion->area_id = $request->area_id;
            $locacion->is_active = $request->is_active;
            $locacion->save();
            return response()->json(["msg" => "Locación actualizada correctamente"], 200);
        } else {
            return response()->json(["msg" => "Locación no encontrada"], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $locacion = Locacion::find($id);
        if ($locacion) {
            $locacion->is_active = false;
            $locacion->save();
            return response()->json(["msg" => "Locación eliminada correctamente"], 200);
        }
        return response()->json(["msg" => "Locación no encontrada"], 404);
    }
}
