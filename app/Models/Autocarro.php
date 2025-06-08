<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autocarro extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricula',
        'marca',
        'modelo',
        'capacidade',
        'cor',
        'numero_chassi',
        'numero_motor',
        'data_fabricacao',
        'data_registo',
        'observacoes',
        'estado',
    ];

    public function motorista()
    {
        return $this->belongsTo(Motorista::class);
    }

    public function viagens()
    {
        return $this->hasMany(Viagen::class);
    }
}
