<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passageiro extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'pessoas_id',
        'nif',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
