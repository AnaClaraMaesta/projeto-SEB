<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Livro extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'titulo',
        'autor',
        'ano_publicacao',
        'codigo',
        'estoque',
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
        static::saving(function (Livro $livro) {
            $livro->status = (int) $livro->estoque > 1
                ? 'Disponível'
                : 'Indisponível';
        });
    }

    public function alunos(): HasMany
    {
        return $this->hasMany(Aluno::class);
    }
}
