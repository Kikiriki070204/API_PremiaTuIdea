<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividades extends Model
{
    use HasFactory;

    protected $table = 'actividades';
    protected $fillable = [
        'id_idea',
        'responsable',
        'fecha_inicio',
        'fecha_finalizacion',
        'id_estado_actividad'
    ];

    public function idea()
    {
        return $this->belongsTo(Idea::class, 'id_idea');
    }

    public function estado_actividad()
    {
        return $this->belongsTo(Estado_Actividad::class, 'id_estado_actividad');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'responsable');
    }
}
