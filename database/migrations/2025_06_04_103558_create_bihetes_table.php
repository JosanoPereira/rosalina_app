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
        Schema::create('bihetes', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_bihete')->unique();
            $table->dateTime('data_emissao');
            $table->dateTime('data_validade');
            $table->foreignId('passageiros_id')
                ->constrained('passageiros')
                ->onDelete('cascade');
            $table->foreignId('viagens_id')
                ->constrained('viagens')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bihetes');
    }
};
