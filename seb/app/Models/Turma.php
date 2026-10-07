<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
        protected $fillable = [
            'id',
            'nome_turma',
            'turno_turma',
            'ano_turma',
            'materia_turma',
            'deleted_at'
        ];
}