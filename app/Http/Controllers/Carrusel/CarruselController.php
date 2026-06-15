<?php

namespace App\Http\Controllers\Carrusel;

use App\Http\Controllers\Controller;
use App\Models\CarruselImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CarruselController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['adminIndex', 'store', 'destroy']);
    }

    public function index()
    {
        $imagenes = CarruselImagen::where('is_active', true)
            ->orderBy('orden')
            ->orderBy('created_at')
            ->get(['id', 'orden', 'mime_type']);

        return response()->json(['imagenes' => $imagenes], 200);
    }

    public function adminIndex()
    {
        $imagenes = CarruselImagen::orderBy('orden')->orderBy('created_at')->get(['id', 'orden', 'mime_type', 'is_active', 'created_at']);
        return response()->json(['imagenes' => $imagenes], 200);
    }

    public function image($id)
    {
        $imagen = CarruselImagen::find($id);

        if (!$imagen) {
            return response()->json(['msg' => 'Imagen no encontrada'], 404);
        }

        if (!Storage::exists($imagen->imagen)) {
            return response()->json(['msg' => 'Archivo no encontrado'], 404);
        }

        $file = Storage::get($imagen->imagen);
        return response($file, 200)->header('Content-Type', $imagen->mime_type);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'imagen' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors(), 'msg' => 'Errores de validación'], 422);
        }

        $file = $request->file('imagen');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/carrusel', $filename);

        $maxOrden = CarruselImagen::max('orden') ?? 0;

        $imagen = CarruselImagen::create([
            'imagen'    => $path,
            'mime_type' => $file->getMimeType(),
            'orden'     => $maxOrden + 1,
            'is_active' => true,
        ]);

        return response()->json(['msg' => 'Imagen subida correctamente', 'id' => $imagen->id], 201);
    }

    public function destroy($id)
    {
        $imagen = CarruselImagen::find($id);

        if (!$imagen) {
            return response()->json(['msg' => 'Imagen no encontrada'], 404);
        }

        Storage::delete($imagen->imagen);
        $imagen->delete();

        return response()->json(['msg' => 'Imagen eliminada correctamente'], 200);
    }
}
