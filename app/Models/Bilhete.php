<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bilhete extends Model
{
    use HasFactory;

    protected $fillable = [
        'viagens_id',
        'passageiros_id',
        'preco',
        'classe',
        'numero_bilhete',
        'data_emissao',
        'data_validade',
    ];

    public function viagen()
    {
        return $this->belongsTo(Viagen::class);
    }

    public function passageiro()
    {
        return $this->belongsTo(Passageiro::class);
    }
}
