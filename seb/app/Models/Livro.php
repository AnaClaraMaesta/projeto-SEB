<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
    ];

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->estoque > 1 ? 'Disponível' : 'Indisponível'
        );
    }

    public function emprestimo(): HasMany
    {
        return $this->hasMany(Emprestimo::class);
    }
}
