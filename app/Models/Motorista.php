<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motorista extends Model
{
    use HasFactory;

    protected $fillable = [
        'pessoas_id',
        'numero_carta',
        'data_emissao',
        'data_validade',
        'categoria',
        'estado',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function autocarro()
    {
        return $this->hasOne(Autocarro::class);
    }

    public function viagens()
    {
        return $this->hasMany(Viagen::class);
    }
}
