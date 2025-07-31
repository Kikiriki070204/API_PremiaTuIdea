<?php

namespace App\Http\Controllers;

use App\Models\EstadoUsuarioPremios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class EstadoUsuarioPremiosController extends Controller
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
        $estado = EstadoUsuarioPremios::all();
        return response()->json(["estado" => $estado], 200);
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
                'nombre' => 'required|string|min:3|max:255|regex:/^[a-zA-Z\s]*$/',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $estado = new EstadoUsuarioPremios();
        $estado->nombre = $request->nombre;
        $estado->save();

        return response()->json(["msg" => "Estado creado correctamente"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $estado = EstadoUsuarioPremios::find($id);
        if ($estado) {
            return response()->json(["estado" => $estado], 200);
        } else {
            return response()->json(["msg" => "Estado no encontrado"], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EstadoUsuarioPremios $usuarioPremios)
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
                'id' => 'required|integer|exists:_estado_usuario_premios,id',
                'nombre' => 'required|string|min:3|max:255|regex:/^[a-zA-Z\s]*$/',
                'activo' => 'required|boolean',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $estado = EstadoUsuarioPremios::where('id', $request->id)->first();

        if (!$estado) {
            return response()->json(["msg" => "Estado no encontrado"], 404);
        }

        $estado->nombre = $request->nombre;
        $estado->activo = $request->activo;
        $estado->save();

        return response()->json(["msg" => "Estado actualizado correctamente"], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $estado = EstadoUsuarioPremios::find($id);
        if ($estado) {
            $estado->activo = false;
            $estado->save();
            return response()->json(["msg" => "Estado eliminado correctamente"], 200);
        } else {
            return response()->json(["msg" => "Estado no encontrado"], 404);
        }
    }

    public function resumenPremios()
    {
        $tabla = DB::table('usuario_premios');

        $premiosEntregados = (clone $tabla)
            ->where('id_estado', 2)
            ->count();

        $premiosEnProceso = (clone $tabla)
            ->where('id_estado', 1)
            ->count();

        $usuariosCanjeadores = (clone $tabla)
            ->distinct('id_usuario')
            ->count('id_usuario');

        $valorTotalCanjeado = DB::table('usuario_premios as up')
            ->join('productos as p', 'up.id_producto', '=', 'p.id')
            ->where('up.id_estado', 2)
            ->sum('p.precio');


        return response()->json([
            'premios_entregados' => $premiosEntregados,
            'premios_en_proceso' => $premiosEnProceso,
            'usuarios_canjeadores' => $usuariosCanjeadores,
            'valor_total_canjeado' => $valorTotalCanjeado
        ]);
    }

    public function top10ProductosEntregados()
    {
        $topProductos = DB::table('usuario_premios as up')
            ->join('productos as p', 'up.id_producto', '=', 'p.id')
            ->select(
                'p.nombre as nombre_producto',
                DB::raw('COUNT(up.id) as cantidad_entregada')
            )
            ->where('up.id_estado', 2)
            ->groupBy('p.id', 'p.nombre')
            ->orderByDesc('cantidad_entregada')
            ->limit(10)
            ->get();

        return response()->json([
            'top_productos_entregados' => $topProductos
        ]);
    }


}
