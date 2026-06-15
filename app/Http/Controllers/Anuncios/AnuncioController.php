<?php

namespace App\Http\Controllers\Anuncios;

use App\Http\Controllers\Controller;
use App\Models\Anuncio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnuncioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function show()
    {
        $anuncio = Anuncio::first();

        if (!$anuncio || !$anuncio->activo || empty($anuncio->mensaje)) {
            return response()->json(['visible' => false], 200);
        }

        if ($anuncio->dias > 0 && $anuncio->fecha_activacion) {
            $expira = \Carbon\Carbon::parse($anuncio->fecha_activacion)->addDays($anuncio->dias);
            if (now()->gt($expira)) {
                return response()->json(['visible' => false], 200);
            }
        }

        return response()->json([
            'visible'    => true,
            'mensaje'    => $anuncio->mensaje,
            'dias'       => $anuncio->dias,
            'updated_at' => (string) $anuncio->updated_at,
        ], 200);
    }

    public function config()
    {
        $anuncio = Anuncio::first();
        if (!$anuncio) {
            return response()->json(['activo' => false, 'mensaje' => '', 'dias' => 0], 200);
        }
        return response()->json([
            'activo'  => (bool) $anuncio->activo,
            'mensaje' => $anuncio->mensaje ?? '',
            'dias'    => $anuncio->dias,
        ], 200);
    }

    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'activo'  => 'required|boolean',
            'mensaje' => 'nullable|string|max:20000',
            'dias'    => 'required|integer|min:0|max:365',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()], 422);
        }

        $anuncio = Anuncio::first();
        if (!$anuncio) {
            $anuncio = new Anuncio();
        }

        $anuncio->activo  = $request->activo;
        $anuncio->mensaje = $request->mensaje ?? '';
        $anuncio->dias    = $request->dias;

        if ($request->activo) {
            $anuncio->fecha_activacion = now()->toDateString();
        }

        $anuncio->save();

        return response()->json(['msg' => 'Anuncio actualizado correctamente'], 200);
    }
}
