<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bilhetes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viagens_id')->constrained('viagens')->onDelete('cascade');
            $table->foreignId('passageiros_id')->constrained('passageiros')->onDelete('cascade');
            $table->decimal('preco', 50, 2);
            $table->string('classe')->default('Económica'); // Classe do bilhete, por exemplo, Económica ou Executiva
            $table->integer('numero_bilhete')->unique();
            $table->dateTime('data_emissao');
            $table->dateTime('data_validade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bilhetes');
    }
};
