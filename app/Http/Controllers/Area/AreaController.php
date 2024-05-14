<?php

namespace App\Http\Controllers\Area;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
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
        $areas = Area::all();
        return response()->json(["areas" => $areas], 200);
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
                'nombre' => 'required|max:255|regex:/^[a-zA-Z0-9\s]+$/u',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $area = new Area();
        $area->nombre = $request->nombre;
        $area->save();
        return response()->json([
            "msg" => "Área creada correctamente"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $area = Area::find($id);
        if ($area) {
            return response()->json(["area" => $area], 200);
        } else {
            return response()->json([
                "msg" => "Área no encontrada"
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area)
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
                'id' => 'required|integer|exists:areas,id',
                'nombre' => 'required|max:255|regex:/^[a-zA-Z0-9\s]+$/u',
                'is_active' => 'required|boolean',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $area = Area::find($request->id);
        $area->nombre = $request->nombre;
        $area->is_active = $request->is_active;
        $area->save();
        return response()->json([
            "msg" => "Área actualizada correctamente"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $area = Area::find($id);
        if ($area) {
            $area->is_active = false;
            $area->save();
            return response()->json([
                "msg" => "Área eliminada correctamente"
            ], 200);
        } else {
            return response()->json([
                "msg" => "Área no encontrada"
            ], 404);
        }
    }
}
