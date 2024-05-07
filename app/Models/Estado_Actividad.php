<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado_Actividad extends Model
{
    use HasFactory;
    protected $table = "estado_actividades";
    protected $fillable = [
        'nombre'
    ];
}
