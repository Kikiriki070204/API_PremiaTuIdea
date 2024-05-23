<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoUsuarioPremios extends Model
{
    use HasFactory;

    protected $table = 'estado_usuario_premios';
    protected $fillable = ['estado', 'activo'];

    public function usuarioPremios()
    {
        return $this->hasMany(UsuarioPremios::class, 'id_estado ');
    }
}
