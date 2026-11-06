<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Turma extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'nome_turma',
        'turno_turma',
        'ano_turma',
    ];

    public function alunos()
    {
        return $this->hasMany(Aluno::class, 'turma_id');
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
