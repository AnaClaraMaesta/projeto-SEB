<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('livros', function (Blueprint $table) {
            $table->unsignedInteger('emprestimos_ativos')->default(0)->after('estoque');
        });
    }

    public function down(): void
    {
        Schema::table('livros', function (Blueprint $table) {
            $table->dropColumn('emprestimos_ativos');
        });
    }
};
