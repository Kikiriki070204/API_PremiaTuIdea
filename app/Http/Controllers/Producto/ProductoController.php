<?php

namespace App\Http\Controllers\Producto;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\ProductosImagenes;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\UsuarioPremiosController;
use App\Models\UsuarioPremios;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
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

        $user = auth('api')->user();

        if (!$user) {
            return response()->json(["msg" => "No estás autorizado"], 401);
        }

        if ($user->rol->id == 1) {
            $productos = Producto::all();
            return response()->json(["productos" => $productos], 200);
        } else {
            $productos = Producto::where('is_active', true)->get();
            return response()->json(["productos" => $productos], 200);
        }
    }

    public function indexAsc()
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(["msg" => "No estás autorizado"], 401);
        }

        $query = Producto::with('imagen')->orderBy('valor', 'asc');

        if ($user->rol->id != 1) {
            $query->where('is_active', true);
        }

        $productos = $query->paginate(10);

        return response()->json(["productos" => $productos], 200);
    }


    public function indexDsc()
    {

        $user = auth('api')->user();

        if (!$user) {
            return response()->json(["msg" => "No estás autorizado"], 401);
        }

        $query = Producto::with('imagen')->orderBy('valor', 'desc');

        if ($user->rol->id != 1) {
            $query->where('is_active', true);
        }

        $productos = $query->paginate(10);

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

                $usuarioPremios = new UsuarioPremios();
                $usuarioPremios->id_usuario = $user->id;
                $usuarioPremios->id_producto = $producto->id;
                $usuarioPremios->id_estado = 1;
                $usuarioPremios->folio = rand(10000, 99999);
                $usuarioPremios->save();

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
    /*
     public function store(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'nombre' => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
                'precio' => 'required|numeric|min:0',
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
    */

    public function store(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'nombre' => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
                'precio' => 'required|numeric|min:0',
                'valor' => 'required|numeric',
                'url' => 'nullable|string|url',
                'imagen' => 'required|image|mimes:jpeg,png,jpg, webp|max:4096',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        DB::beginTransaction();

        try {
            $producto = new Producto();
            $producto->nombre = $request->nombre;
            $producto->valor = $request->valor;
            $producto->precio = $request->precio;
            $producto->url = $request->url;
            $producto->save();

            // Guardar imagen
            if ($request->hasFile('imagen')) {
                $file = $request->file('imagen');

                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/productos', $filename);

                $productoImagen = new ProductosImagenes();
                $productoImagen->producto_id = $producto->id;
                $productoImagen->imagen = $path;
                $productoImagen->mime_type = $file->getMimeType();
                $productoImagen->save();
            }

            DB::commit();

            return response()->json([
                "msg" => "Producto creado correctamente",
                "producto_id" => $producto->id
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error($e);

            return response()->json([
                "msg" => "Error al crear el producto",
                "error" => $e->getMessage()
            ], 500);
        }
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
                'nombre' => 'required|string|max:255',
                'valor' => 'required|numeric',
                'precio' => 'required|numeric|min:0',
                'url' => 'nullable|string|url',
                'is_active' => 'required|boolean',
                'imagen' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $producto = Producto::find($request->id);
        if (!$producto) {
            return response()->json([
                "msg" => "Producto no encontrado"
            ], 404);
        }


        $producto->id = $request->id;
        $producto->nombre = $request->nombre;
        $producto->valor = $request->valor;
        $producto->precio = $request->precio;
        $producto->url = $request->url;
        $producto->is_active = $request->is_active;
        $producto->save();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $mimeType = $file->getMimeType();

            $imagenAnterior = ProductosImagenes::where('producto_id', $producto->id)->first();

            if ($imagenAnterior) {
                $rutaAnterior = str_replace('public/', '', $imagenAnterior->imagen);

                if (Storage::disk('public')->exists($rutaAnterior)) {
                    Storage::disk('public')->delete($rutaAnterior);
                }
            }

            $path = $file->store('productos', 'public');
            $ruta = 'public/' . $path;

            ProductosImagenes::updateOrCreate(
                ['producto_id' => $request->id],
                [
                    'producto_id' => $request->id,
                    'imagen' => $ruta,
                    'mime_type' => $mimeType
                ]
            );
        }




        return response()->json([
            "msg" => "Producto actualizado correctamente"
        ], 200);
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
