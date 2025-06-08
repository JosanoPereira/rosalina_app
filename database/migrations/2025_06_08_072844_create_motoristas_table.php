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
        Schema::create('motoristas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pessoas_id')->constrained('pessoas')->onDelete('cascade');
            $table->string('numero_carta')->unique();
            $table->date('data_emissao')->nullable();
            $table->date('data_validade')->nullable();
            $table->string('categoria')->nullable();
            $table->string('estado')->default('activo'); // activo, inactivo, suspenso
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motoristas');
    }
};
