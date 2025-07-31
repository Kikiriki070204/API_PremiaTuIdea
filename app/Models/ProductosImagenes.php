<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductosImagenes extends Model
{
    use HasFactory;

    protected $table = 'productos_imagenes';
    protected $fillable = [
        'produto_id',
        'imagen',
        'mime_type',
        'is_active',
    ];

    public function producto()
    {
        return $this->belongsToMany(Producto::class, 'producto_id');
    }
}
