<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarruselImagen extends Model
{
    use HasFactory;

    protected $table = 'carrusel_imagenes';

    protected $fillable = [
        'imagen',
        'mime_type',
        'orden',
        'is_active',
    ];
}
