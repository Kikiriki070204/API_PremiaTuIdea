<?php

namespace App\Http\Controllers\Ideas;

use App\Http\Controllers\Controller;
use App\Models\IdeasImagenes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class IdeasImagenesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $imagenes = IdeasImagenes::all();
        return response()->json($imagenes);
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
        $validator = Validator::make($request->all(), [
            'idea_id' => 'required|integer|exists:ideas,id',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $image = $request->file('imagen');
        $imageData = file_get_contents($image->getPathName());
        $base64Image = base64_encode($imageData);


        $imagen = new IdeasImagenes();
        $imagen->idea_id = $request->idea_id;
        $imagen->imagen = $base64Image;
        $imagen->mime_type = $image->getMimeType();
        $imagen->save();

        return response()->json(['message' => 'Imagen guardada correctamente']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $image = IdeasImagenes::where('idea_id', $id)->first();

        if (!$image) {
            return response()->json(['message' => 'Imagen no encontrada'], 404);
        }

        return response()->make(base64_decode($image->imagen), 200, [
            'Content-Type' => $image->mime_type,
            'Content-Disposition' => 'inline; filename="' . $image->id . '.' . $image->mime_type . '"'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IdeasImagenes $ideasImagenes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idea_id' => 'required|integer|exists:ideas,id',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $imagen = IdeasImagenes::find($request->id);
        if ($imagen) {

            $imagen->is_active = $request->is_active;
            $imagen->save();

            return response()->json(['message' => 'Imagen actualizada correctamente']);
        } else {
            return response()->json(['message' => 'Imagen no encontrada'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $imagen = IdeasImagenes::find($id);
        if ($imagen) {
            $imagen->is_active = false;
            $imagen->save();
            return response()->json(['message' => 'Imagen eliminada correctamente']);
        } else {
            return response()->json(['message' => 'Imagen no encontrada'], 404);
        }
    }
}
