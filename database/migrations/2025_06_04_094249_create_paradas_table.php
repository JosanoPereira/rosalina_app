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
        Schema::create('paradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rotas_id')
                ->constrained('rotas')
                ->onDelete('cascade');
            $table->string('nome');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paradas');
    }
};
