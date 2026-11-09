<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Emprestimo;
use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmprestimoController extends Controller
{
    /**
     * Lista os empréstimos do usuário logado (o global scope do model já filtra).
     */
    public function index()
    {
        $this->authorize('viewAny', Emprestimo::class);

        $emprestimo = Emprestimo::with(['aluno.turma', 'livro'])
            ->orderByRaw('CASE WHEN data_devolucao IS NULL THEN 0 ELSE 1 END')
            ->orderBy('data_devolucao_prevista')
            ->paginate(15)
            ->withQueryString();

        return view('emprestimo.index', compact('emprestimo'));
    }

    /**
     * Registra um novo empréstimo.
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            'aluno_id' => 'required|integer|exists:alunos,id',
            'livro_id' => 'required|integer|exists:livros,id',
            'dias_prazo' => 'required|integer|min:1|max:30',
        ]);

        // O global scope de Aluno impede emprestar para aluno de outro usuário.
        $aluno = Aluno::findOrFail($dados['aluno_id']);

        $this->authorize('create', [Emprestimo::class, $aluno]);

        if ($aluno->temAtraso() && ! $request->boolean('confirmado')) {
            throw ValidationException::withMessages([
                'pendencia' => 'Este aluno possui empréstimo em atraso. Confirme para realizar um novo empréstimo.',
            ]);
        }

        DB::transaction(function () use ($aluno, $dados) {
            $livro = Livro::lockForUpdate()->findOrFail($dados['livro_id']);

            // Regra de negócio: o último exemplar permanece na biblioteca.
            if ($livro->estoque <= 1) {
                throw ValidationException::withMessages([
                    'livro_id' => 'Este livro está indisponível. O último exemplar deve permanecer na biblioteca.',
                ]);
            }

            Emprestimo::create([
                'aluno_id' => $aluno->id,
                'livro_id' => $livro->id,
                'data_emprestimo' => today(),
                'data_devolucao_prevista' => today()->addDays((int) $dados['dias_prazo']),
            ]);

            $livro->decrement('estoque');
            $livro->increment('emprestimos_ativos');
        });

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Empréstimo realizado com sucesso!']);
        }

        return redirect()->route('emprestimo.index')
        ->with('success', 'Empréstimo realizado com sucesso!');
      
    }

    /**
     * Registra a devolução de um empréstimo.
     */
    public function devolver(Request $request, Emprestimo $emprestimo)
    {
        $this->authorize('update', $emprestimo);

        if ($emprestimo->data_devolucao) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Este livro já foi devolvido.'], 422);
            }

            return back()->with('error', 'Este livro já foi devolvido.');
        }

        DB::transaction(function () use ($emprestimo) {
            $emprestimo = Emprestimo::lockForUpdate()->findOrFail($emprestimo->id);

            if ($emprestimo->data_devolucao) {
                return;
            }

            $livro = $emprestimo->livro()->lockForUpdate()->first();

            $emprestimo->update(['data_devolucao' => today()]);

            // O livro pode ter sido removido do acervo; nesse caso não há estoque a restaurar.
            if ($livro) {
                $livro->increment('estoque');
                $livro->forceFill([
                    'emprestimos_ativos' => max(0, (int) $livro->emprestimos_ativos - 1),
                ])->save();
            }
        });

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Devolução registrada com sucesso!']);
        }

        return back()->with('success', 'Devolução registrada com sucesso!');
    }
}
