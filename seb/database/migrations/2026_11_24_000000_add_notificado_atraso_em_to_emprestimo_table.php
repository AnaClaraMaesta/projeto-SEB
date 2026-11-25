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
        Schema::table('emprestimo', function (Blueprint $table) {
            // Data do último e-mail de atraso enviado, para não repetir no mesmo dia.
            $table->date('notificado_atraso_em')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emprestimo', function (Blueprint $table) {
            $table->dropColumn('notificado_atraso_em');
        });
    }
};
