<?php

namespace App\Http\Controllers\Ideas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Idea;
use Illuminate\Support\Facades\Validator;

class IdeasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }



    public function index()
    {
        $user = auth()->user();

        if ($user) {
            $ideas = Idea::all();

            return response()->json(["ideas" => $ideas], 200);
        }
        return response()->json(["msg" => "No estás autorizado"], 401);
    }

    public function create(Request $request)
    {
        $user = auth()->user();

        if ($user) {
            $validate = Validator::make(
                $request->all(),
                [
                    'titulo' => 'required|min:5',
                    'antecedentes' => 'required| max: 2000',
                    //'condicion_actual'
                    'propuesta' => 'required|max: 2000',
                    //equipo_id
                ]
            );

            if ($validate->fails()) {
                return response()->json([
                    "errors" => $validate->errors(),
                    "msg" => "Errores de validación"
                ], 422);
            }

            $idea = new Idea();

            $idea->user_id = $user->id;
            $idea->titulo = $request->titulo;
            $idea->antecedentes = $request->antecedentes;
            $idea->propuesta = $request->propuesta;

            $idea->save();
        }
        return response()->json(["msg" => "No estás autorizado"], 401);
    }
}
