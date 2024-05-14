<?php

namespace App\Http\Controllers\Departamento;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartamentoController extends Controller
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
        $departamentos = Departamento::all();
        return response()->json(["departamentos" => $departamentos], 200);
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

        $departamento = new Departamento();
        $departamento->nombre = $request->nombre;
        $departamento->save();
        return response()->json([
            "msg" => "Departamento creado correctamente"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $departamento = Departamento::find($id);
        if ($departamento) {
            return response()->json(["departamento" => $departamento], 200);
        }
        return response()->json(["msg" => "Departamento no encontrado"], 404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departamento $departamento)
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
                'id' => 'required|integer|exists:departamentos,id',
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

        $departamento = Departamento::find($request->id);
        if ($departamento) {
            $departamento->nombre = $request->nombre;
            $departamento->is_active = $request->is_active;
            $departamento->save();
            return response()->json([
                "msg" => "Departamento actualizado correctamente"
            ], 200);
        }
        return response()->json(["msg" => "Departamento no encontrado"], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $departamento = Departamento::find($id);
        if ($departamento) {
            $departamento->is_active = false;
            $departamento->save();
            return response()->json([
                "msg" => "Departamento eliminado correctamente"
            ], 200);
        }
        return response()->json(["msg" => "Departamento no encontrado"], 404);
    }
}
