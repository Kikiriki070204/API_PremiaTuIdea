<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    use HasFactory;
    protected $table = 'historials';
    protected $fillable = ['user_id', 'puntos', 'is_active'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
