<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdeasResultados extends Model
{
    use HasFactory;

    protected $table = 'ideas_resultados';

    protected $fillable = [
        'idea_id',
        'imagen',
        'mime_type',
    ];
}
