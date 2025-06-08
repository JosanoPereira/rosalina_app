<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'apelido',
        'bi',
        'telefone',
        'nascimento',
        'generos_id',
    ];

    public function genero()
    {
        return $this->belongsTo(Genero::class);
    }
}
