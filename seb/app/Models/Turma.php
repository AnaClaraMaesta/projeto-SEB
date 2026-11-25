<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Turma extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'professor_id',
        'nome_turma',
        'turno_turma',
        'ano_turma',
        'cor',
    ];

    /**
     * Cor de texto que mantém contraste legível sobre a cor personalizada.
     * Sem cor definida, o tema claro/escuro cuida do contraste.
     */
    protected function corTexto(): Attribute
    {
        return Attribute::make(
            get: function () {
                $cor = ltrim((string) $this->cor, '#');

                if (strlen($cor) !== 6 || ! ctype_xdigit($cor)) {
                    return '#ffffff';
                }

                $r = hexdec(substr($cor, 0, 2));
                $g = hexdec(substr($cor, 2, 2));
                $b = hexdec(substr($cor, 4, 2));

                $luminancia = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

                return $luminancia > 0.6 ? '#111827' : '#ffffff';
            }
        );
    }

    public function alunos()
    {
        return $this->hasMany(Aluno::class, 'turma_id');
    }

    public function professor(): BelongsTo
    {
        return $this->belongsTo(Professor::class, 'professor_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        // Aplica o filtro de user_id automaticamente em todas as queries
        static::addGlobalScope('user', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('user_id', Auth::id());
            }
        });

        static::deleting(function (Turma $turma) {
            // Não permite excluir a turma enquanto houver empréstimo em aberto.
            if ($turma->alunos()
                ->whereHas('emprestimo', fn (Builder $query) => $query->whereNull('data_devolucao'))
                ->exists()) {
                return false;
            }

            $turma->alunos()->delete();
        });

        // Atribui o user_id automaticamente na hora de criar
        static::creating(function ($turma) {
            if (Auth::check() && ! $turma->user_id) {
                $turma->user_id = Auth::id();
            }
        });

        static::restoring(function (Turma $turma) {
            $turma->alunos()->withTrashed()->restore();
        });
    }
}
