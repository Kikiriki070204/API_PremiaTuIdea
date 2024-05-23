<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioPremios extends Model
{
    use HasFactory;

    protected $table = 'usuario_premios';
    protected $fillable = ['id_usuario', 'id_producto', 'id_estado', 'folio'];

    public function usuario()
    {
        return $this->belongsToMany(Usuario::class, 'id_usuario');
    }

    public function producto()
    {
        return $this->belongsToMany(Producto::class, 'id_producto');
    }

    public function estadoUsuarioPremios()
    {
        return $this->belongsTo(EstadoUsuarioPremios::class, 'id_estado');
    }
}
