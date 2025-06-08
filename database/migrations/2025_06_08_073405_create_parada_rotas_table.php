<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parada_rotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paradas_id')->constrained('paradas')->onDelete('cascade');
            $table->foreignId('rotas_id')->constrained('rotas')->onDelete('cascade');
            $table->time('hora_partida')->nullable();
            $table->time('hora_chegada')->nullable();
            $table->enum('frequencia', ['diaria', 'semanal', 'mensal', 'anual'])->default('diaria');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parada_rotas');
    }
};
