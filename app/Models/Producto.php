<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $table = 'productos';
    protected $fillable = [
        'nombre',
        'valor',
        'precio',
        'url'
    ];

    public function usuarioPremios()
    {
        return $this->hasMany(UsuarioPremios::class, 'id_producto');
    }

    public function imagen()
    {
        return $this->hasOne(ProductosImagenes::class, 'producto_id');
    }

}
