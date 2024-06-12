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
        'propuesta',
        'estatus',
        'user_id',
        'ahorro',
        'contable',
        'area_id',
        'campos_id',
        'fecha_inicio',
        'fecha_fin',
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

    public function imagenes()
    {
        return $this->hasMany(IdeasImagenes::class, 'idea_id');
    }

    public function campos()
    {
        return $this->belongsToMany(Campos::class, 'campos_id');
    }

    public function area()
    {
        return $this->belongsToMany(Area::class, 'area_id');
    }

    public function camposideas()
    {
        return $this->hasMany(Campos_Idea::class, 'idea_id');
    }
}
