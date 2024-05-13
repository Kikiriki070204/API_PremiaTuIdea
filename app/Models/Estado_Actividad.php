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


    public function actividades()
    {
        return $this->hasMany(Actividades::class, 'id_estado_actividad');
    }
}
