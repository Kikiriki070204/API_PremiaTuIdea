<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;

class AuthController extends Controller
{

    public function password(Request $request)
    {
        $validate = Validator::make($request->all(),
        [
            'ibm' =>'required',
            'password' => 'required|min:6'
        ]);

        if($validate->fails())
        {
            return response()->json(["errors"=>$validate->errors(),
            "msg"=>"Errores de validación"],422);
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
            "user" => $user
        ], 200);
    }

    public function login()
    {
        $credentials = request(['ibm', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['token'=>$token],200);
    }
}
