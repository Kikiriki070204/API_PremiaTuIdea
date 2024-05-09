<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Area;
use App\Models\User;

class Locacion extends Model
{
    use HasFactory;
    protected $table = "locaciones";
    protected $fillable = [
        'nombre',
        'area_id'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function user()
    {
        return $this->hasMany(User::class, 'locacion_id');
    }
}
