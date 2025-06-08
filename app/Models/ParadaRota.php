<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParadaRota extends Model
{
    use HasFactory;

    protected $fillable = [
        'paradas_id',
        'rotas_id',
        'hora_partida',
        'hora_chegada',
        'frequencia',
    ];

    public function parada()
    {
        return $this->belongsTo(Parada::class);
    }

    public function rota()
    {
        return $this->belongsTo(Rota::class);
    }
}
