<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /**
     * Carrega as contagens e os empréstimos ativos de cada aluno (evita N+1).
     */
    public function scopeComEmprestimos(Builder $query): Builder
    {
        return $query
            ->withCount([
                'emprestimo as emprestimos_total',
                'emprestimo as devolucoes_total' => fn ($q) => $q->whereNotNull('data_devolucao'),
            ])
            ->with(['emprestimo' => fn ($q) => $q->whereNull('data_devolucao')->with('livro')]);
    }

    /**
     * Status calculado a partir da tabela emprestimo (não usa a coluna legada alunos.status).
     */
    protected function statusEmprestimo(): Attribute
    {
        return Attribute::make(
            get: function () {
                $ativos = $this->relationLoaded('emprestimo')
                    ? $this->emprestimo->whereNull('data_devolucao')
                    : $this->emprestimo()->whereNull('data_devolucao')->get();

                if ($ativos->isEmpty()) {
                    return 'Sem empréstimo';
                }

                if ($ativos->contains(fn (Emprestimo $item) => $item->data_devolucao_prevista?->lt(today()))) {
                    return 'Atrasado';
                }

                return 'No prazo';
            }
        );
    }
}
