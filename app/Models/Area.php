<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Locacion;
use App\Models\User;

class Area extends Model
{
    use HasFactory;
    protected $table = "areas";
    protected $fillable = [
        'nombre'
    ];

    public function locacion()
    {
        return $this->hasMany(Locacion::class, 'area_id');
    }

    public function user()
    {
        return $this->hasMany(User::class, 'area_id');
    }

    public function ideas()
    {
        return $this->hasMany(Idea::class, 'area_id');
    }
}
