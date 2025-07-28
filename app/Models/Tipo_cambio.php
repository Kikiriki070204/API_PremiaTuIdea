<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_cambio extends Model
{
    use HasFactory;

    protected $table = 'tipo_de_cambio';

    protected $fillable = [
        'moneda_origen',
        'valor',
    ];


}
