<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;


class Turma extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'nome_turma',
        'turno_turma',
        'ano_turma',
        'materia_turma',
    ];

    public function alunos()
    {
        return $this->hasMany(aluno::class);
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

        // Atribui o user_id automaticamente na hora de criar
        static::creating(function ($turma) {
            if (Auth::check() && ! $turma->user_id) {
                $turma->user_id = Auth::id();
            }
        });
    }

}