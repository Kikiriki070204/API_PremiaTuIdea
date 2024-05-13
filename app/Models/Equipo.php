<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;
    protected $table = 'equipos';
    protected $fillable = [
        'nombre',
        'id_idea',
        'is_active'
    ];

    public function idea()
    {
        return $this->belongsTo(Idea::class, 'id_idea');
    }

    public function usuariosEquipos()
    {
        return $this->hasMany(Usuario_Equipo::class, 'id_equipo');
    }
}
