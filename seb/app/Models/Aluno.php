<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Aluno extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'telefone',
        'turma_id',
        'livro_id',
        'data_emprestimo',
        'data_devolucao',
        'data_devolucao_prevista',
        'status',
    ];

    protected $casts = [
        'livro_id' => 'integer',
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

    /**
     * Livro gravado diretamente no aluno (dados legados/manuais).
     */
    public function livro(): BelongsTo
    {
        return $this->belongsTo(Livro::class)->withTrashed();
    }

    public function emprestimo(): HasMany
    {
        return $this->hasMany(Emprestimo::class);
    }

    /**
     * Empréstimo em aberto mais recente do aluno.
     */
    public function emprestimoAtivo(): HasOne
    {
        return $this->hasOne(Emprestimo::class)
            ->whereNull('data_devolucao')
            ->latestOfMany();
    }

    /**
     * O status exibido prioriza o empréstimo em aberto e ignora a coluna
     * legada quando não existe empréstimo ativo.
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                $emprestimo = $this->emprestimoAtivo;

                if (! $emprestimo) {
                    return 'Sem emprestimo';
                }

                if ($emprestimo->data_devolucao_prevista?->lt(today())) {
                    return 'Atrasado';
                }

                return 'No prazo';
            }
        );
    }

    /**
     * Rótulo amigável do status exibido nas telas e relatórios.
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->status) {
                'No prazo' => 'Empréstimo ativo',
                'Sem emprestimo' => 'Sem empréstimo',
                default => $this->status,
            }
        );
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
