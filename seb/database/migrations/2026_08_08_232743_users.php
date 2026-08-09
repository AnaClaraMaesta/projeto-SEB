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
        Schema::create('users', function(Blueprint $table){
            $table->string('nome', 255);
            $table->string('email', 255)->unique();
            $table->string('senha', 255); //mudar dps --> precisa de criptografia
            $table->enum('cargo', ['bibliotecaria', 'visitante'. 'usuario']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
