<?php

namespace Database\Seeders;

use App\Models\Genero;
use Illuminate\Database\Seeder;

class GeneroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $generos = [
            'Masculino',
            'Feminino',
            'Outro',
        ];

        foreach ($generos as $genero => $key) {
            $genero = Genero::where('genero', $key)->first();
            if (!$genero) {
                Genero::create([
                    'genero' => $key,
                ]);
            }
        }
    }
}
