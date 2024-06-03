<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Estado_Idea;

class Idea extends Model
{
    use HasFactory;
    protected $table = 'ideas';
    protected $fillable = [
        'titulo',
        'antecedentes',
        'condiciones',
        'propuesta',
        'estatus',
        'user_id',
        //'equipo_id'
    ];

    public function estatus()
    {
        return $this->belongsTo(Estado_Idea::class, 'estatus');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function actividades()
    {
        return $this->hasMany(Actividades::class, 'id_idea');
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'id_idea');
    }
}
