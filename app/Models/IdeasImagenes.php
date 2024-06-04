<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdeasImagenes extends Model
{
    use HasFactory;

    protected $table = 'ideas_imagenes';
    protected $fillable = [
        'idea_id',
        'imagen',
        'mime_type',
        'is_active',
    ];

    public function idea()
    {
        return $this->belongsToMany(Idea::class, 'idea_id');
    }
}
