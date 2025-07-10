<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
use Yaza\LaravelGoogleDriveStorage\Gdrive;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'registro', 'password', 'register']]);
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
        if ($user->password == null) {
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
                "msg" => "Usuario ya registrado"
            ], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();
        return $user;
    }

    public function register(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'ibm' => 'required|integer',
                'nombre' => 'required|string|max:255|regex:/^[a-zA-Z\s]*$/',
                'password' => 'required|min:8',
                'departamento_id' => 'nullable|integer',
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

        if ($request->departamento_id == 0 || $request->departamento_id == null) {
            $user = new Usuario();
            $user->ibm = $request->ibm;
            $user->nombre = $request->nombre;
            $user->password = $request->password;
            $user->rol_id = 4;
            $user->departamento_id = null;
            $user->area_id = $request->area_id;
            $user->locacion_id = $request->locacion_id;
            $user->save();
        } else {
            $user = new Usuario();
            $user->ibm = $request->ibm;
            $user->nombre = $request->nombre;
            $user->password = $request->password;
            $user->rol_id = 4;
            $user->departamento_id = $request->departamento_id;
            $user->area_id = $request->area_id;
            $user->locacion_id = $request->locacion_id;
            $user->turno = $request->turno;
            $user->save();
        }
        return response()->json([
            "msg" => "Usuario creado correctamente"
        ], 201);
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
            'expires_in' => JWTAuth::factory()->getTTL() * 60, // Aquí no me generaba token y decidí cambiarlo por esta alternativa
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
            ->where('usuarios.id', auth('api')->user()->id)
            ->first();

        return $user;
    }
}
