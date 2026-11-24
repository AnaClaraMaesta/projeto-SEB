<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Professor extends Model
{
    use SoftDeletes;

    protected $table = 'professores';

    protected $fillable = [
        'user_id',
        'nome',
        'email',
        'telefone',
    ];

    public function turmas(): HasMany
    {
        return $this->hasMany(Turma::class, 'professor_id');
    }

    protected static function booted(): void
    {
        // Cada usuário enxerga apenas os professores que cadastrou.
        static::addGlobalScope('user', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('user_id', Auth::id());
            }
        });

        static::creating(function (Professor $professor) {
            if (Auth::check() && ! $professor->user_id) {
                $professor->user_id = Auth::id();
            }
        });

        // Ao remover o professor, as turmas ficam sem professor responsável.
        static::deleting(function (Professor $professor) {
            $professor->turmas()->update(['professor_id' => null]);
        });
    }
}
