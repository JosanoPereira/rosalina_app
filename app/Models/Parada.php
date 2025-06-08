<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parada extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'ordem',
    ];

    public function paradaRota()
    {
        return $this->hasMany(ParadaRota::class);
    }

}
