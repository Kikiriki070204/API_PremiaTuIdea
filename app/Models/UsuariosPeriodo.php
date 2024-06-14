<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuariosPeriodo extends Model
{
    use HasFactory;

    protected $table = 'usuarios_periodo';
    protected $fillable = ['user_id', 'puntos', 'fecha', 'is_active'];

    public function usuario()
    {
        return $this->belongsToMany(Usuario::class, 'user_id');
    }
}
