<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Rol extends Model
{
    use HasFactory;
    protected $table = "roles";
    protected $fillable = [
        'nombre'
    ];

    public function Usuario()
    {
        return $this->hasMany(User::class, 'rol_id');
    }
}
