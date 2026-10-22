<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Turma extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome_turma',
        'turno_turma',
        'ano_turma',
        'materia_turma',
    ];

    public function alunos()
    {
        return $this->hasMany(aluno::class);
    }

}