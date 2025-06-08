<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Viagen extends Model
{
    use HasFactory;

    protected $fillable = [
        'rotas_id',
        'motoristas_id',
        'autocarros_id',
        'hora_partida',
        'hora_chegada',
    ];

    public function rota()
    {
        return $this->belongsTo(Rota::class);
    }

    public function motorista()
    {
        return $this->belongsTo(Motorista::class);
    }

    public function autocarro()
    {
        return $this->belongsTo(Autocarro::class);
    }

    public function bilhetes()
    {
        return $this->hasMany(Bilhete::class);
    }
}
