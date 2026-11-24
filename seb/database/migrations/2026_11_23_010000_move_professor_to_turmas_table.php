<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // A turma passa a apontar para o professor responsável.
        Schema::table('turmas', function (Blueprint $table) {
            $table->foreignId('professor_id')->nullable()->after('user_id')
                ->constrained('professores')->nullOnDelete();
        });

        // O professor deixa de pertencer a uma única turma e passa a ter dono.
        Schema::table('professores', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')
                ->constrained()->cascadeOnDelete();
        });

        // Preserva as associações já existentes antes de remover a coluna antiga.
        DB::table('professores')
            ->select('id', 'turma_id')
            ->whereNotNull('turma_id')
            ->orderBy('id')
            ->chunkById(100, function ($professores) {
                foreach ($professores as $professor) {
                    $turma = DB::table('turmas')->where('id', $professor->turma_id)->first();

                    if (! $turma) {
                        continue;
                    }

                    DB::table('professores')
                        ->where('id', $professor->id)
                        ->update(['user_id' => $turma->user_id]);

                    DB::table('turmas')
                        ->where('id', $turma->id)
                        ->whereNull('professor_id')
                        ->update(['professor_id' => $professor->id]);
                }
            });

        Schema::table('professores', function (Blueprint $table) {
            $table->dropConstrainedForeignId('turma_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('professores', function (Blueprint $table) {
            $table->foreignId('turma_id')->nullable()->after('id')
                ->constrained('turmas')->nullOnDelete();
        });

        DB::table('turmas')
            ->select('id', 'professor_id')
            ->whereNotNull('professor_id')
            ->orderBy('id')
            ->chunkById(100, function ($turmas) {
                foreach ($turmas as $turma) {
                    DB::table('professores')
                        ->where('id', $turma->professor_id)
                        ->whereNull('turma_id')
                        ->update(['turma_id' => $turma->id]);
                }
            });

        Schema::table('turmas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('professor_id');
        });

        Schema::table('professores', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
