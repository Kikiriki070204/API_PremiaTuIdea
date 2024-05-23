<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\aceptacion;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'registro', 'password']]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function password(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'ibm' => 'required|integer',
                'password' => 'required|min:6'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors(),
                "msg" => "Errores de validación"
            ], 422);
        }

        $user = User::where('ibm', $request->ibm)->first();

        if (!$user) {
            return response()->json([
                "msg" => "Usuario no encontrado"
            ], 404);
        }

        $user->password = $request->password;

        $user->save();
        return response()->json([
            "msg" => "Contraseña actualizada correctamente"
        ], 200);
    }

    public function login()
    {

        $user = User::where('ibm', request('ibm'))->first();

        if (!$user) {
            return response()->json([
                "msg" => "Usuario no encontrado"
            ], 404);
        }

        if ($user->is_active == false) {
            return response()->json([
                "msg" => "Usuario no activo"
            ], 401);
        }
        if ($user->password == null || $user->email == null) {
            return response()->json([
                "msg" => "Usuario no registrado"
            ], 401);
        }

        $credentials = request(['ibm', 'password']);

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function registro(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'ibm' => 'required|integer',
                'password' => 'required|min:6',
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                "errors" => $validate->errors()
            ], 422);
        }

        $user = User::where('ibm', $request->ibm)->first();

        if (!$user) {
            return response()->json([
                "msg" => "Usuario no encontrado o Usuario ya registrado"
            ], 404);
        }

        if ($user->password != null) {
            return response()->json([
                "msg" => "Usuario no encontrado o Usuario ya registrado"
            ], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();
        return $user;
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    protected function respondWithToken($token)
    {
        $user = auth('api')->user();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }


    public function verifyToken(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        try {
            $user = JWTAuth::setToken($token)->authenticate();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['valid' => true, 'rol' => $user->rol_id], 200);
    }


    public function meplus()
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
            ->where('usuarios.id', auth('api')->user()->id)
            ->first();

        return $user;
    }
}
