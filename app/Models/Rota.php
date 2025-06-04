<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rota extends Model
{
    protected $fillable = ['origem', 'destino', 'distancia', 'tempo_estimado', 'waypoints'];
    protected $casts = [
        'waypoints' => 'array',
    ];
}
