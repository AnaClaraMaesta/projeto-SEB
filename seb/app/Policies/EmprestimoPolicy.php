<?php

namespace App\Policies;

use App\Models\Aluno;
use App\Models\Emprestimo;
use App\Models\User;

class EmprestimoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Emprestimo $emprestimo): bool
    {
        return $emprestimo->aluno?->turma?->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Aluno $aluno): bool
    {
        return $aluno->turma?->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Emprestimo $emprestimo): bool
    {
        return $emprestimo->aluno?->turma?->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Emprestimo $emprestimo): bool
    {
        return false;
    }
}
