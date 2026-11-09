<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Emprestimo extends Model
{
    use SoftDeletes;

    protected $table = 'emprestimo';

    protected $fillable = [
        'aluno_id',
        'livro_id',
        'data_emprestimo',
        'data_devolucao_prevista',
        'data_devolucao',
    ];

    protected $casts = [
        'data_emprestimo' => 'date',
        'data_devolucao_prevista' => 'date',
        'data_devolucao' => 'date',
    ];

    protected static function booted(): void
    {
        // Somente empréstimos de alunos que pertencem às turmas do usuário logado.
        static::addGlobalScope('user_turmas', function (Builder $builder) {
            if (Auth::check()) {
                $builder->whereHas('aluno.turma', function ($query) {
                    $query->where('user_id', Auth::id());
                });
            }
        });
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (! empty($attributes['data_devolucao'])) {
                    return 'Devolvido';
                }

                if (! empty($attributes['data_devolucao_prevista'])
                    && Carbon::parse($attributes['data_devolucao_prevista'])->lt(today())) {
                    return 'Atrasado';
                }

                return 'No prazo';
            }
        );
    }

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function livro(): BelongsTo
    {
        // Mantém o histórico visível mesmo que o livro tenha sido removido (soft delete).
        return $this->belongsTo(Livro::class)->withTrashed();
    }

    /**
     * Empréstimos que ainda não foram devolvidos.
     */
    public function scopeAtivos(Builder $query): Builder
    {
        return $query->whereNull('data_devolucao');
    }

    /**
     * Empréstimos ativos com a data prevista já vencida.
     */
    public function scopeAtrasados(Builder $query): Builder
    {
        return $query->ativos()->whereDate('data_devolucao_prevista', '<', today());
    }
}
