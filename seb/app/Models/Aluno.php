<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Aluno extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'telefone',
        'livro_id',
        'turma_id',
        'data_emprestimo',
        'data_devolucao',
        'data_devolucao_prevista',
        'status',
    ];

    protected $casts = [
        'data_emprestimo' => 'date',
        'data_devolucao' => 'date',
        'data_devolucao_prevista' => 'date',
    ];

    protected static function booted()
    {
        static::addGlobalScope('user_turmas', function (Builder $builder) {
            if (Auth::check() && Auth::user()) {
                // Filtra alunos cujas turmas pertencem ao usuário logado
                $builder->whereHas('turma', function ($query) {
                    $query->where('user_id', Auth::id());
                });
            }
        });
    }

    public function turma(): BelongsTo
    {
        return $this->belongsTo(Turma::class, 'turma_id');
    }

    public function livro(): BelongsTo
    {
        return $this->belongsTo(Livro::class, 'livro_id');
    }
}
