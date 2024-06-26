<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'ibm',
        'nombre',
        'departamento_id',
        'area_id',
        'locacion_id',
        'rol_id',
        'password',
        'puntos'
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function actividades()
    {
        return $this->hasMany(Actividades::class, 'responsable');
    }

    public function locacion()
    {
        return $this->belongsTo(Locacion::class, 'locacion_id');
    }

    public function usuariosEquipos()
    {
        return $this->hasMany(Usuario_Equipo::class, 'id_usuario');
    }

    public function usuarioPremios()
    {
        return $this->hasMany(UsuarioPremios::class, 'id_usuario');
    }

    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'usuario_equipos', 'id_usuario', 'id_equipo');
    }

    public function historial()
    {
        return $this->hasOne(Historial::class, 'user_id');
    }

    public function usuariosPeriodo()
    {
        return $this->hasMany(UsuariosPeriodo::class, 'user_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
