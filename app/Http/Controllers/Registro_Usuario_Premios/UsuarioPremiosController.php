<?php

namespace App\Http\Controllers;

use App\Models\UsuarioPremios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UsuarioPremiosController extends Controller
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
        $user = auth()->user();

        if ($user) {
            if ($user->rol == 1)
                $premios = DB::table('usuario_premios')
                    ->join('usuarios', 'usuario_premios.id_usuario', '=', 'usuarios.id')
                    ->join('productos', 'usuario_premios.id_producto', '=', 'productos.id')
                    ->join('estado_usuario_premios', 'usuario_premios.id_estado', '=', 'estado_usuario_premios.id')
                    ->select('usuario_premios.*', 'usuarios.nombre as usuario', 'productos.nombre as producto', 'estado_usuario_premios.nombre as estado')
                    ->get();
            else
                $premios = DB::table('usuario_premios')
                    ->join('usuarios', 'usuario_premios.id_usuario', '=', 'usuarios.id')
                    ->join('productos', 'usuario_premios.id_producto', '=', 'productos.id')
                    ->join('estado_usuario_premios', 'usuario_premios.id_estado', '=', 'estado_usuario_premios.id')
                    ->select('usuario_premios.*', 'usuarios.nombre as usuario', 'productos.nombre as producto', 'estado_usuario_premios.nombre as estado')
                    ->where('usuario_premios.id_usuario', $user->id)
                    ->get();
            return response()->json(["premios" => $premios], 200);
        }
        return response()->json(["msg" => "No estás autorizado"], 401);
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

    //Este se utiliza automaticamente cuando el usuario canjea sus puntos por algun producto
    public function store(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'id_usuario' => 'required|integer|exists:usuarios,id',
                'id_producto' => 'required|integer|exists:productos,id',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $premio = new UsuarioPremios();
        $premio->id_usuario = $request->id_usuario;
        $premio->id_producto = $request->id_producto;
        $premio->folio = rand(10000, 99999);
        $premio->save();

        return response()->json(["msg" => "Premio creado correctamente"], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $usuariopremio = DB::table('usuario_premios')
            ->join('usuarios', 'usuario_premios.id_usuario', '=', 'usuarios.id')
            ->join('productos', 'usuario_premios.id_producto', '=', 'productos.id')
            ->join('estado_usuario_premios', 'usuario_premios.id_estado', '=', 'estado_usuario_premios.id')
            ->select('usuario_premios.*', 'usuarios.nombre as usuario', 'productos.nombre as producto', 'estado_usuario_premios.nombre as estado')
            ->where('usuario_premios.id', $id)
            ->first();

        if ($usuariopremio) {
            return response()->json(["premio" => $usuariopremio], 200);
        }
        return response()->json(["msg" => "Premio no encontrado"], 404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UsuarioPremios $esdtadoUsuarioPremios)
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
                'id' => 'required|integer|exists:usuario_premios,id',
                'id_usuario' => 'required|integer|exists:usuarios,id',
                'id_producto' => 'required|integer|exists:productos,id',
                'id_estado' => 'required|integer|exists:estado_usuario_premios,id',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $premio = UsuarioPremios::where('id', $request->id)->first();

        if (!$premio) {
            return response()->json(["msg" => "Premio no encontrado"], 404);
        }

        $premio->id_estado = $request->id_estado;
        $premio->save();

        return response()->json(["msg" => "Premio actualizado correctamente"], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $premio = UsuarioPremios::where('id', $id)->first();

        if ($premio) {
            $premio->id_estado = 3;
            return response()->json(["msg" => "Premio eliminado correctamente"], 200);
        }
        return response()->json(["msg" => "Premio no encontrado"], 404);
    }
}
