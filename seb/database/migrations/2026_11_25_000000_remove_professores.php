<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * O professor passou a ser o próprio usuário (dono da turma), então a
     * estrutura separada de professores deixou de existir.
     */
    public function up(): void
    {
        if (Schema::hasColumn('turmas', 'professor_id')) {
            Schema::table('turmas', function (Blueprint $table) {
                // No PostgreSQL a constraint de FK é removida junto com a coluna.
                $table->dropColumn('professor_id');
            });
        }

        Schema::dropIfExists('professores');
    }

    /**
     * Recurso removido: não há necessidade de recriar a estrutura antiga.
     */
    public function down(): void
    {
        //
    }
};
