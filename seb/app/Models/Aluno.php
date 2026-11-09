<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Aluno extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'telefone',
        'turma_id',
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

    public function emprestimo(): HasMany
    {
        return $this->hasMany(Emprestimo::class);
    }

    /**
     * Verifica se o aluno possui algum empréstimo ativo já vencido.
     */
    public function temAtraso(): bool
    {
        return $this->emprestimo()
            ->whereNull('data_devolucao')
            ->whereDate('data_devolucao_prevista', '<', today())
            ->exists();
    }
}
