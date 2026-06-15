<?php

namespace App\Http\Controllers\Terminos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TerminosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['imagen', 'index']);
    }

    public function index()
    {
        $secciones = DB::table('terminos_secciones')->get()->keyBy('seccion_key');
        $imagenes  = DB::table('terminos_imagenes')->orderBy('orden')->get()->groupBy('seccion_key');

        $result = [];
        foreach ($secciones as $key => $sec) {
            $imgs = $imagenes->get($key, collect());
            $result[$key] = [
                'bases_html'      => $sec->bases_html,
                'premiacion_html' => $sec->premiacion_html,
                'imagenes'        => $imgs->map(fn($img) => [
                    'id'    => $img->id,
                    'url'   => url("/api/terminos/imagen/{$img->id}"),
                    'orden' => $img->orden,
                ])->values(),
            ];
        }

        return response()->json(['secciones' => $result]);
    }

    public function update(Request $request, $seccion)
    {
        $user = auth('api')->user();
        if (!$user || $user->rol_id != 1) {
            return response()->json(['msg' => 'No autorizado'], 403);
        }

        $request->validate([
            'bases_html'      => 'nullable|string',
            'premiacion_html' => 'nullable|string',
        ]);

        DB::table('terminos_secciones')->updateOrInsert(
            ['seccion_key' => $seccion],
            [
                'bases_html'      => $request->bases_html,
                'premiacion_html' => $request->premiacion_html,
                'updated_at'      => now(),
                'created_at'      => now(),
            ]
        );

        return response()->json(['msg' => 'Sección actualizada correctamente']);
    }

    public function imagen($id)
    {
        $img = DB::table('terminos_imagenes')->where('id', $id)->first();
        if (!$img) {
            return response()->json(['msg' => 'No encontrada'], 404);
        }

        $file = Storage::get($img->imagen);
        if (!$file) {
            return response()->json(['msg' => 'Archivo no encontrado'], 404);
        }

        return response($file, 200)->header('Content-Type', $img->mime_type);
    }

    public function uploadImagen(Request $request, $seccion)
    {
        $user = auth('api')->user();
        if (!$user || $user->rol_id != 1) {
            return response()->json(['msg' => 'No autorizado'], 403);
        }

        $request->validate([
            'imagen' => 'required|file|image|mimes:jpeg,png,jpg|max:4900',
            'orden'  => 'nullable|integer|min:1',
        ]);

        $file           = $request->file('imagen');
        $uniqueFilename = 'terminos_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path           = $file->storePubliclyAs('public/images/terminos', $uniqueFilename);
        $orden          = $request->input('orden');

        if ($orden) {
            $existing = DB::table('terminos_imagenes')
                ->where('seccion_key', $seccion)
                ->where('orden', $orden)
                ->first();

            if ($existing) {
                Storage::delete($existing->imagen);
                DB::table('terminos_imagenes')->where('id', $existing->id)->update([
                    'imagen'    => $path,
                    'mime_type' => $file->getMimeType(),
                ]);
                $id = $existing->id;
            } else {
                $id = DB::table('terminos_imagenes')->insertGetId([
                    'seccion_key' => $seccion,
                    'orden'       => $orden,
                    'imagen'      => $path,
                    'mime_type'   => $file->getMimeType(),
                    'created_at'  => now(),
                ]);
            }
        } else {
            $orden = (DB::table('terminos_imagenes')->where('seccion_key', $seccion)->max('orden') ?? 0) + 1;
            $id    = DB::table('terminos_imagenes')->insertGetId([
                'seccion_key' => $seccion,
                'orden'       => $orden,
                'imagen'      => $path,
                'mime_type'   => $file->getMimeType(),
                'created_at'  => now(),
            ]);
        }

        return response()->json([
            'id'    => $id,
            'url'   => url("/api/terminos/imagen/{$id}"),
            'orden' => (int) $orden,
        ], 201);
    }

    public function deleteImagen($id)
    {
        $user = auth('api')->user();
        if (!$user || $user->rol_id != 1) {
            return response()->json(['msg' => 'No autorizado'], 403);
        }

        $img = DB::table('terminos_imagenes')->where('id', $id)->first();
        if (!$img) {
            return response()->json(['msg' => 'No encontrada'], 404);
        }

        Storage::delete($img->imagen);
        DB::table('terminos_imagenes')->where('id', $id)->delete();

        return response()->json(['msg' => 'Imagen eliminada']);
    }
}
