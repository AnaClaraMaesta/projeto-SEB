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
       Schema::table('alunos', function (Blueprint $table) {
            if (!Schema::hasColumn('alunos', 'livro_id')) {
                $table->foreignId('livro_id')->constrained();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            if (Schema::hasColumn('alunos', 'livro_id')) {
                $table->dropForeign(['livro_id']);
                $table->dropColumn('livro_id');
            }
        });
    }
};
