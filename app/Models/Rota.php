<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rota extends Model
{
    use HasFactory;

    protected $fillable = ['origem', 'destino', 'distancia', 'tempo_estimado', 'waypoints'];

    protected $casts = [
        'waypoints' => 'array',
    ];

    public function viagens()
    {
        return $this->hasMany(Viagen::class);
    }

    public function paradaRota()
    {
        return $this->hasMany(ParadaRota::class);
    }
}
