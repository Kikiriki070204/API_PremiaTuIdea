<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
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
        $roles = Rol::all();
        return response()->json(["roles" => $roles], 200);
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
        $validte = Validator::make(
            $request->all(),
            [
                'nombre' => 'required|max:255|regex:/^[a-zA-Z]+$/u',
            ]
        );

        if ($validte->fails()) {
            return response()->json([
                "errors" => $validte->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $rol = new Rol();
        $rol->nombre = $request->nombre;
        $rol->save();
        return response()->json([
            "msg" => "Rol creado correctamente"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $rol = Rol::find($id);
        if (!$rol) {
            return response()->json([
                "msg" => "Rol no encontrado"
            ], 404);
        }
        return response()->json(["rol" => $rol], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rol $rol)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validte = Validator::make(
            $request->all(),
            [
                'nombre' => 'required|max:255|regex:/^[a-zA-Z]+$/u',
                'id' => 'required|integer|exists:roles,id',
                'is_active' => 'required|boolean',
            ]
        );

        if ($validte->fails()) {
            return response()->json([
                "errors" => $validte->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $rol = Rol::find($request->id);
        if (!$rol) {
            return response()->json([
                "msg" => "Rol no encontrado"
            ], 404);
        }

        $rol->nombre = $request->nombre;
        $rol->is_active = $request->is_active;
        $rol->save();
        return response()->json([
            "msg" => "Rol actualizado correctamente"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rol = Rol::find($id);
        if ($rol) {
            $rol->is_active = false;
            $rol->save();
            return response()->json([
                "msg" => "Rol eliminado correctamente"
            ], 200);
        }
        return response()->json([
            "msg" => "Rol no encontrado"
        ], 404);
    }
}
