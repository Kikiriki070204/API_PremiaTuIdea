<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario_Equipo extends Model
{
    use HasFactory;

    protected $table = 'usuarios_equipos';
    protected $fillable = [
        'id_usuario',
        'id_equipo',
        'is_active'
    ];

    public function usuario()
    {
        return $this->belongsToMany(User::class, 'id_usuario');
    }

    public function equipo()
    {
        return $this->belongsToMany(Equipo::class, 'id_equipo');
    }
}
