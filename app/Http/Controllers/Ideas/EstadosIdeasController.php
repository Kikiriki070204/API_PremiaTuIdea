<?php

namespace App\Http\Controllers\Ideas;

use App\Http\Controllers\Controller;
use App\Models\Estado_Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EstadosIdeasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estados = Estado_Idea::all();
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

        $estado = new Estado_Idea();
        $estado->nombre = $request->nombre;
        $estado->save();
        return response()->json([
            "msg" => "Estado creado correctamente"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $estado = Estado_Idea::find($id);
        if (!$estado) {
            return response()->json([
                "msg" => "Estado no encontrado"
            ], 404);
        }
        return response()->json(["estado" => $estado], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estado_Idea $estado_Idea)
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
                'id' => 'required|integer|exists:estado_ideas,id',
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

        $estado = Estado_Idea::find($request->id);
        $estado->nombre = $request->nombre;
        $estado->is_active = $request->is_active;
        $estado->save();
        return response()->json([
            "msg" => "Estado actualizado correctamente"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $estado = Estado_Idea::find($id);
        if (!$estado) {
            return response()->json([
                "msg" => "Estado no encontrado"
            ], 404);
        }
        $estado->is_active = false;
        $estado->save();
        return response()->json([
            "msg" => "Estado eliminado correctamente"
        ], 200);
    }
}
