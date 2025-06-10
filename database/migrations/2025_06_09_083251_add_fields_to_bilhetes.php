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
        Schema::table('bilhetes', function (Blueprint $table) {
            $table->boolean('activo')->default(true)->after('numero_bilhete');
            $table->boolean('pago')->default(false)->after('activo');
            $table->foreignId('passageiros_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bilhetes', function (Blueprint $table) {
            $table->dropColumn('activo');
            $table->dropColumn('pago');
            $table->foreignId('passageiros_id')->nullable(false)->change();

        });
    }
};
