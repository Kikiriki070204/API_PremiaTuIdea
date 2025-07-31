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

    /*
   public function index()
   {
       $user = auth('api')->user();

       if ($user) {
           $premios = DB::table('usuario_premios')
               ->join('usuarios', 'usuario_premios.id_usuario', '=', 'usuarios.id')
               ->join('productos', 'usuario_premios.id_producto', '=', 'productos.id')
               ->join('estado_usuario_premios', 'usuario_premios.id_estado', '=', 'estado_usuario_premios.id')
               ->select('usuario_premios.*', 'usuarios.nombre as usuario', 'productos.nombre as producto', 'productos.url as url', 'estado_usuario_premios.estado as estado')
               ->where('usuario_premios.id_usuario', $user->id)
               ->get();

           return response()->json(["premios" => $premios], 200);
       }
       return response()->json(["msg" => "No estás autorizado"], 401);
   }
   */

    public function index()
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(["msg" => "No estás autorizado"], 401);
        }

        $premios = DB::table('usuario_premios')
            ->join('usuarios', 'usuario_premios.id_usuario', '=', 'usuarios.id')
            ->join('productos', 'usuario_premios.id_producto', '=', 'productos.id')
            ->leftJoin('productos_imagenes', 'productos.id', '=', 'productos_imagenes.producto_id')
            ->join('estado_usuario_premios', 'usuario_premios.id_estado', '=', 'estado_usuario_premios.id')
            ->select(
                'usuario_premios.*',
                'usuarios.nombre as usuario',
                'productos.nombre as producto',
                'productos.url as url',
                'productos_imagenes.imagen as imagen',
                'estado_usuario_premios.estado as estado'
            )
            ->where('usuario_premios.id_usuario', $user->id)
            ->get();

        return response()->json(["premios" => $premios], 200);


    }



    public function indexAdmin()
    {
        $user = auth('api')->user();

        if ($user) {

            if ($user->rol->id == 1) {
                $premios = DB::table('usuario_premios')
                    ->join('usuarios', 'usuario_premios.id_usuario', '=', 'usuarios.id')
                    ->join('productos', 'usuario_premios.id_producto', '=', 'productos.id')
                    ->join('estado_usuario_premios', 'usuario_premios.id_estado', '=', 'estado_usuario_premios.id')
                    ->select('usuario_premios.*', 'usuarios.nombre as usuario', 'productos.nombre as producto', 'productos.url as url', 'estado_usuario_premios.estado as estado')
                    ->paginate(10);
            }

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
            ->select('usuario_premios.*', 'usuarios.nombre as usuario', 'productos.nombre as producto', 'productos.url as url', 'estado_usuario_premios.estado as estado')
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

    public function usuariosBonos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        $tipoCambioUSD = DB::table('tipo_de_cambio')
            ->where('moneda_origen', 'USD')
            ->orderByDesc('created_at')
            ->value('valor');

        if (!$tipoCambioUSD) {
            return response()->json(['error' => 'Tipo de cambio USD no encontrado'], 400);
        }

        $bonosQuery = DB::table('usuarios_bonos')
            ->join('usuarios', 'usuarios.id', '=', 'usuarios_bonos.usuario_id');

        if ($fechaInicio && $fechaFin) {
            $bonosQuery->whereBetween('usuarios_bonos.created_at', [$fechaInicio, $fechaFin]);
        }

        $topUsuarios = $bonosQuery
            ->select(
                'usuarios.id as usuario_id',
                'usuarios.nombre',
                DB::raw('SUM(usuarios_bonos.bono) as bono_mxn'),
                DB::raw('ROUND(SUM(usuarios_bonos.bono) / ' . $tipoCambioUSD . ', 2) as bono_usd')
            )
            ->groupBy('usuarios.id', 'usuarios.nombre')
            ->orderByDesc('bono_mxn')
            ->limit(10)
            ->get();

        $totalBonosQuery = DB::table('usuarios_bonos');
        if ($fechaInicio && $fechaFin) {
            $totalBonosQuery->whereBetween('created_at', [$fechaInicio, $fechaFin]);
        }

        $bonoTotalMXN = $totalBonosQuery->sum('bono');
        $bonoTotalUSD = round($bonoTotalMXN / $tipoCambioUSD, 2);

        return response()->json([
            'tipo_cambio_usd' => $tipoCambioUSD,
            'bono_total_mxn' => $bonoTotalMXN,
            'bono_total_usd' => $bonoTotalUSD,
            'top_usuarios' => $topUsuarios
        ]);
    }


}
