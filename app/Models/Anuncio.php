<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anuncio extends Model
{
    protected $table = 'anuncios';
    protected $fillable = ['activo', 'mensaje', 'dias', 'fecha_activacion'];
    protected $casts = ['activo' => 'boolean'];
}
