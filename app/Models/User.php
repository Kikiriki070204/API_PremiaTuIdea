<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Rol;
use App\Models\Departamento;
use App\Models\Area;
use App\Models\Locacion;
use App\Models\Idea;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
     protected $table = 'usuarios'; 
 
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
 
     public function locacion()
     {
         return $this->belongsTo(Locacion::class, 'locacion_id');
     }

     public function getJWTIdentifier()
     {
         return $this->getKey();
     }
 
     public function getJWTCustomClaims()
     {
         return [];
     }
     
     public function idea()
     {
        return $this->hasMany(Idea::class,'user_id');
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
