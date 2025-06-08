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
        Schema::create('viagens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rotas_id')->constrained()->onDelete('cascade');
            $table->foreignId('motoristas_id')->constrained()->onDelete('cascade');
            $table->foreignId('autocarros_id')->constrained()->onDelete('cascade');
            $table->time('hora_partida')->nullable();
            $table->time('hora_chegada')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viagens');
    }
};
