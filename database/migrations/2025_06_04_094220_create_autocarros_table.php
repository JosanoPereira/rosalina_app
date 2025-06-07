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
        Schema::create('autocarros', function (Blueprint $table) {
            $table->id();
            $table->string('matricula')->unique();
            $table->string('marca');
            $table->string('modelo');
            $table->integer('capacidade');
            $table->string('cor')->nullable();
            $table->string('numero_chassi')->unique();
            $table->string('numero_motor')->unique();
            $table->date('data_fabricacao')->nullable();
            $table->date('data_registo')->nullable();
            $table->string('observacoes')->nullable();
            $table->enum('estado', ['ativo', 'inativo'])->default('ativo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autocarros');
    }
};
