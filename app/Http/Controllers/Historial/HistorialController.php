<?php

namespace App\Http\Controllers\Historial;

use App\Http\Controllers\Controller;
use App\Models\Historial;
use App\Models\UsuariosPeriodo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistorialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date'
            ]
        );

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }

        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        $historial = DB::table('usuarios_periodos')
            ->join('usuarios', 'usuarios_periodos.user_id', '=', 'usuarios.id')
            ->select('usuarios_periodos.user_id', 'usuarios.ibm', 'usuarios.nombre', DB::raw('SUM(usuarios_periodos.puntos) as total_puntos'))
            ->where('usuarios_periodos.is_active', true)
            ->where('usuarios.is_active', true)
            ->whereBetween('usuarios_periodos.fecha', [$fechaInicio, $fechaFin])
            ->groupBy('usuarios_periodos.user_id', 'usuarios.ibm', 'usuarios.nombre')
            ->orderBy('total_puntos', 'desc')
            ->limit(10)
            ->get();
        return response()->json(["historial" => $historial], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fecha = date('Y-m-d', strtotime('2024-06-01'));
        $historiales = Historial::all();

        foreach ($historiales as $historial) {
            if (!is_null($historial->user_id)) {
                UsuariosPeriodo::create([
                    'user_id' => $historial->user_id,
                    'puntos' => $historial->puntos,
                    'fecha' => $fecha,
                    'is_active' => true
                ]);
            } else {
                return response()->json(["error" => "No se encontro el usuario"], 404);
            }
        }

        return response()->json(["msg" => "Se crearon los historiales correctamente"], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario_id' => 'required|integer',
            'puntos' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 400);
        }

        $historial = Historial::create([
            'usuario_id' => $request->usuario_id,
            'puntos' => $request->puntos,
        ]);

        return response()->json(["msg" => "Se agrego el nuevo historial correctamente"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $historial = Historial::find($id);
        if (!$historial) {
            return response()->json(["error" => "No se encontro el historial"], 404);
        }
        return response()->json(["historial" => $historial], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Historial $historial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:historial,id',
            'usuario_id' => 'required|integer',
            'puntos' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 400);
        }

        $historial = Historial::find($request->id);
        $historial->usuario_id = $request->usuario_id;
        $historial->puntos = $request->puntos;
        $historial->is_active = $request->is_active;
        $historial->save();

        return response()->json(["msg" => "Se actualizo el historial correctamente"], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $historial = Historial::find($id);
        if (!$historial) {
            return response()->json(["error" => "No se encontro el historial"], 404);
        }
        $historial->is_active = false;
        $historial->save();
        return response()->json(["msg" => "Se elimino el historial correctamente"], 200);
    }
}
