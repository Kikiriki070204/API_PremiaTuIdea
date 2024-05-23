<?php

namespace App\Http\Controllers\Producto;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UsuarioPremiosController;

class ProductoController extends Controller
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
        $productos = Producto::all();
        return response()->json(["productos" => $productos], 200);
    }

    public function canjear(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(["msg" => "No estás autorizado"], 401);
        }
        $validate = Validator::make(
            $request->all(),
            [
                'id' => 'required|integer|exists:productos,id',
            ]
        );
        $producto = Producto::find($request->id);
        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        if ($producto) {
            if ($user->puntos >= $producto->valor) {
                $usuario = Usuario::find($user->id);
                $usuario->puntos -= $producto->valor;
                $usuario->save();

                $usuarioPremiosController = new UsuarioPremiosController();
                $usuarioPremiosController->store($user->id, $producto->id);

                return response()->json([
                    "msg" => "Producto canjeado correctamente"
                ], 200);
            } else {
                return response()->json([
                    "msg" => "No tienes suficientes puntos para canjear este producto"
                ], 400);
            }
        } else {
            return response()->json([
                "msg" => "Producto no encontrado"
            ], 404);
        }
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
                'valor' => 'required|numeric',
                'url' => 'nullable|string|url',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->valor = $request->valor;
        $producto->url = $request->url;
        $producto->save();
        return response()->json([
            "msg" => "Producto creado correctamente"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $producto = Producto::find($id);
        if ($producto) {
            return response()->json(["producto" => $producto], 200);
        } else {
            return response()->json(["msg" => "Producto no encontrado"], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
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
                'id' => 'required|integer|exists:productos,id',
                'nombre' => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
                'valor' => 'required|numeric',
                'url' => 'nullable|string|url',
                'is_active' => 'required|boolean',

            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $producto = Producto::find($request->id);
        if ($producto) {
            $producto->nombre = $request->nombre;
            $producto->valor = $request->valor;
            $producto->url = $request->url;
            $producto->is_active = $request->is_active;
            $producto->save();
            return response()->json([
                "msg" => "Producto actualizado correctamente"
            ], 200);
        } else {
            return response()->json([
                "msg" => "Producto no encontrado"
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $producto = Producto::find($id);
        if ($producto) {
            $producto->is_active = false;
            $producto->save();
            return response()->json([
                "msg" => "Producto eliminado correctamente"
            ], 200);
        } else {
            return response()->json([
                "msg" => "Producto no encontrado"
            ], 404);
        }
    }
}
