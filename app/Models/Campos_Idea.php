<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campos_Idea extends Model
{
    use HasFactory;
    protected $table = 'campos__ideas';
    protected $fillable = ['idea_id', 'campo_id', 'is_active'];

    public function ideas()
    {
        return $this->belongsToMany(Campos_Idea::class, 'ideas', 'idea_id', 'id');
    }

    public function campos()
    {
        return $this->belongsToMany(Campos_Idea::class, 'campos', 'campo_id', 'id');
    }
}
