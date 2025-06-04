<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $fillable = [
        'nome',
        'apelido',
        'bi',
        'telefone',
        'nascimento',
        'generos_id',
    ];
}
