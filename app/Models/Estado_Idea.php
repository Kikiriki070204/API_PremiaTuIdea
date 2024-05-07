<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Idea;

class Estado_Idea extends Model
{
    use HasFactory;
    protected $table = "estado_ideas";
    protected $fillable = [
        'nombre'
    ];

    public function idea()
     {
        return $this->hasMany(Idea::class,'estatus');
     }
}
