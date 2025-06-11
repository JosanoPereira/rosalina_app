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
        Schema::table('viagens', function (Blueprint $table) {
            $table->decimal('preco', 50, 2)->default(0)->after('autocarros_id');
        });

        Schema::table('bilhetes', function (Blueprint $table) {
            $table->dropColumn('preco');
            $table->integer('qtd')->default(1)->after('passageiros_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('viagens', function (Blueprint $table) {
            $table->dropColumn('preco');
        });

        Schema::table('bilhetes', function (Blueprint $table) {
            $table->decimal('preco', 50, 2)->default(0)->after('passageiros_id');
            $table->dropColumn('qtd');
        });
    }
};
