<?php

namespace App\Models;

use Google\Service\Ideahub\Resource\Ideas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campos extends Model
{
    use HasFactory;
    protected $table = 'campos';
    protected $fillable = ['nombre', 'is_active'];

    public function ideas()
    {
        return $this->hasMany(Ideas::class, 'campos_id');
    }
}
