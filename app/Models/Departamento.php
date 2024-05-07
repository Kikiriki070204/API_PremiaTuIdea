<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Departamento extends Model
{
    use HasFactory;
    protected $table = "departamentos";
    protected $fillable = [
        'nombre'
    ];

    public function usuario()
    {
        return $this->hasMany(User::class, 'departamento_id');
    }
}
