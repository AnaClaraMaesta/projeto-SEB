<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Turma;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Aluno::class);

        $alunos = Aluno::with(['turma', 'emprestimoAtivo.livro', 'livro'])
            ->orderBy('nome')
            ->get();

        return view('aluno.index', compact('alunos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Turma $turma)
    {
        $this->authorize('create', [Aluno::class, $turma]);

        $dados = $this->validatedData($request);

        $turma->alunos()->create($dados);

        return redirect()
            ->route('turma.show', $turma)
            ->with('success', 'Aluno cadastrado!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Turma $turma, Aluno $aluno)
    {
        abort_unless((int) $aluno->turma_id === (int) $turma->id, 404);

        $this->authorize('update', $aluno);

        $dados = $this->validatedData($request);

        $aluno->update($dados);

        return redirect()
            ->route('turma.show', $turma)
            ->with('success', 'Aluno atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Turma $turma, Aluno $aluno)
    {
        abort_unless((int) $aluno->turma_id === (int) $turma->id, 404);

        $this->authorize('delete', $aluno);

        if ($aluno->emprestimo()->whereNull('data_devolucao')->exists()) {
            return redirect()
                ->route('turma.show', $turma)
                ->with('error', 'Este aluno possui empréstimo em aberto e não pode ser excluído.');
        }

        $aluno->delete();

        return redirect()
            ->route('turma.show', $turma)
            ->with('success', 'Aluno removido!');
    }

    public function gerarPDF(Turma $turma)
    {
        $this->authorize('view', $turma);

        $alunos = Aluno::where('turma_id', $turma->id)
            ->with(['turma', 'emprestimoAtivo.livro', 'livro'])
            ->orderBy('nome')
            ->get();

        $pdf = Pdf::loadView('aluno.pdf', compact('alunos', 'turma'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('alunos_'.$turma->nome_turma.'.pdf');
    }

    public function gerarPdfGeral()
    {
        $this->authorize('viewAny', Aluno::class);

        $alunos = Aluno::whereHas('turma')
            ->with(['turma', 'emprestimoAtivo.livro', 'livro'])
            ->orderBy('nome')
            ->get();

        $pdf = Pdf::loadView('aluno.pdf', compact('alunos'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('alunos_geral.pdf');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:11',
            'livro_id' => 'nullable|integer|exists:livros,id',
            'data_emprestimo' => 'nullable|date',
            'data_devolucao' => 'nullable|date',
            'data_devolucao_prevista' => 'nullable|date',
            'status' => ['nullable', 'in:Sem emprestimo,No prazo,Atrasado'],
        ]);
    }
}
