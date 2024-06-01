<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class UsersController extends Controller
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
        $users = Usuario::all();
        return response()->json(["users" => $users], 200);
    }

    public function colaboradores()
    {
        $user = auth()->user();

        $users = DB::table('usuarios')
            ->join('roles', 'usuarios.rol_id', '=', 'roles.id')
            ->leftJoin('departamentos', 'usuarios.departamento_id', '=', 'departamentos.id')
            ->join('areas', 'usuarios.area_id', '=', 'areas.id')
            ->leftJoin('locaciones', 'usuarios.locacion_id', '=', 'locaciones.id')
            ->select('usuarios.*', 'roles.nombre as rol', 'departamentos.nombre as departamento', 'areas.nombre as area', 'locaciones.nombre as locacion')
            ->where('usuarios.is_active', true)
            ->where('usuarios.id', '!=', $user->id)
            ->get();

        return response()->json(["users" => $users], 200);
    }

    public function allUsers()
    {
        
            $user = auth()->user();
    
            $users = DB::table('usuarios')
                ->join('roles', 'usuarios.rol_id', '=', 'roles.id')
                ->leftJoin('departamentos', 'usuarios.departamento_id', '=', 'departamentos.id')
                ->join('areas', 'usuarios.area_id', '=', 'areas.id')
                ->leftJoin('locaciones', 'usuarios.locacion_id', '=', 'locaciones.id')
                ->select('usuarios.*', 'roles.nombre as rol', 'departamentos.nombre as departamento', 'areas.nombre as area', 'locaciones.nombre as locacion')
                ->where('usuarios.id', '!=', $user->id)
                ->get();
    
            return response()->json(["users" => $users], 200);
        
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
                'ibm' => 'required|integer',
                'nombre' => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
                'rol_id' => 'required|integer|exists:roles,id',
                'departamento_id' => 'required|integer|exists:departamentos,id',
                'area_id' => 'required|integer|exists:areas,id',
                'locacion_id' => 'nullable|integer|exists:locaciones,id',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $user = new Usuario();
        $user->ibm = $request->ibm;
        $user->nombre = $request->nombre;
        $user->rol_id = $request->rol_id;
        $user->departamento_id = $request->departamento_id;
        $user->area_id = $request->area_id;
        $user->locacion_id = $request->locacion_id;
        $user->save();
        return response()->json([
            "msg" => "Usuario creado correctamente"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = DB::table('usuarios')
            ->join('roles', 'usuarios.rol_id', '=', 'roles.id')
            ->join('departamentos', 'usuarios.departamento_id', '=', 'departamentos.id')
            ->join('areas', 'usuarios.area_id', '=', 'areas.id')
            ->leftJoin('locaciones', 'usuarios.locacion_id', '=', 'locaciones.id')
            ->select(
                'usuarios.*',
                'roles.nombre as rol',
                'departamentos.nombre as departamento',
                'areas.nombre as area',
                'locaciones.nombre as locacion'
            )
            ->where('usuarios.id', $id)
            ->first();

        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
                'id' => 'required|integer|exists:usuarios,id',
                'ibm' => 'required|integer',
                'nombre' => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
                'rol_id' => 'required|integer|exists:roles,id',
                'departamento_id' => 'required|integer|exists:departamentos,id',
                'area_id' => 'required|integer|exists:areas,id',
                'is_active' => 'required|boolean',
                'locacion_id' => 'nullable|integer|exists:locaciones,id',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $user = Usuario::where('id', $request->id)->first();
        if (!$user) {
            return response()->json(["msg" => "Usuario no encontrado"], 404);
        }

        $user->ibm = $request->ibm;
        $user->nombre = $request->nombre;
        $user->rol_id = $request->rol_id;
        $user->departamento_id = $request->departamento_id;
        $user->area_id = $request->area_id;
        $user->is_active = $request->is_active;
        $user->locacion_id = $request->locacion_id;
        $user->save();
        return response()->json([
            "msg" => "Usuario actualizado correctamente"
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Usuario::find($id);
        if ($user) {
            $user->is_active = false;
            $user->save();
            return response()->json(["msg" => "Usuario eliminado correctamente"], 200);
        } else {
            return response()->json(["msg" => "Usuario no encontrado"], 404);
        }
    }

    public function nombre(Request $request)
    {
        $user = auth('api')->user();

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

        $users = DB::table('usuarios')
            ->join('roles', 'usuarios.rol_id', '=', 'roles.id')
            ->leftJoin('departamentos', 'usuarios.departamento_id', '=', 'departamentos.id')
            ->join('areas', 'usuarios.area_id', '=', 'areas.id')
            ->leftJoin('locaciones', 'usuarios.locacion_id', '=', 'locaciones.id')
            ->select(
                'usuarios.*',
                'roles.nombre as rol',
                'departamentos.nombre as departamento',
                'areas.nombre as area',
                'locaciones.nombre as locacion'
            )
            ->where('usuarios.nombre', 'like', '%' . $request->nombre . '%')
            ->where('usuarios.id', '!=', $user->id)
            ->get();
        return response()->json(["users" => $users], 200);
    }
}
