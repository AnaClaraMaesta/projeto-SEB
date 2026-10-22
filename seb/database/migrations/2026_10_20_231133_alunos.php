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
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turma_id')->nullable()->constrained('turmas')->cascadeOnDelete();
            $table->string('nome');
            $table->string('telefone');
            $table->string('endereco');
            $table->foreignId('livro_id')->nullable()->constrained('livros')->nullOnDelete();
            $table->date('data_emprestimo')->nullable();
            $table->date('data_devolucao')->nullable();
            $table->date('data_devolucao_prevista')->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};
