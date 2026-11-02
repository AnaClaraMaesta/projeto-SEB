<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class aluno extends Model
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
        'status'
    ];

    protected $casts = [
        'data_emprestimo' => 'date',
        'data_devolucao' => 'date',
        'data_devolucao_prevista' => 'date',
    ];

    public function turma()
    {
        return $this->belongsTo(Turma::class, 'turma_id');    
    }

    public function livro(): BelongsTo
    {
        return $this->belongsTo(Livro::class, 'livro_id');
    }
}
