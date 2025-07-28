<?php

namespace App\Http\Controllers\Campos;

use App\Http\Controllers\Controller;
use App\Models\Campos;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CamposController extends Controller
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
        if (auth()->user()->rol_id != 1) {
            $campos = Campos::where('is_active', true)->get();
            return response()->json(["campos" => $campos], 200);
        }
        $campos = Campos::all();
        return response()->json(["campos" => $campos], 200);
    }

    public function monetario($num)
    {
        if ($num == 1) {
            $campos = Campos::where('monetario', '!=', 2)->get();
            return response()->json(["campos" => $campos], 200);
        }

        if ($num == 2) {
            $campos = Campos::where('monetario', '!=', 1)->get();
            return response()->json(["campos" => $campos], 200);
        }
        return response()->json(["error" => "No se encontraron campos monetarios"], 404);
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
        // N. 1 = No Monetario, N. 2 = Monetario, N. 3 = Ambos(mixto)
        $monetario = ['1', '2', '3'];
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'monetario' => 'required|in:' . implode(',', $monetario),
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 400);
        }

        $campos = Campos::create([
            'nombre' => $request->nombre,
            'monetario' => $request->monetario,
        ]);

        return response()->json(["msg" => "Se agrego el nuevo campo correctamente"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $campos = Campos::find($id);
        if (!$campos) {
            return response()->json(["error" => "Campo no encontrado"], 404);
        }
        return response()->json(["campo" => $campos], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campos $campos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // N. 1 = No Monetario, N. 2 = Monetario, N. 3 = Ambos(mixto)
        $monetario = ['1', '2', '3'];
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:campos,id',
            'nombre' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'monetario' => 'required|in:' . implode(',', $monetario),
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 400);
        }

        $campos = Campos::find($request->id);
        if (!$campos) {
            return response()->json(["error" => "Campo no encontrado"], 404);
        }

        $campos->nombre = $request->nombre;
        $campos->is_active = $request->is_active;
        $campos->monetario = $request->monetario;
        $campos->save();
        return response()->json(["msg" => "Campo actualizado correctamente"], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $campos = Campos::find($id);
        if (!$campos) {
            return response()->json(["error" => "Campo no encontrado"], 404);
        }
        $campos->is_active = false;
        $campos->save();
        return response()->json(["msg" => "Campo eliminado correctamente"], 200);
    }
}
