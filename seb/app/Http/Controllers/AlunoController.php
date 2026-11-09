<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Livro;
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
        $alunos = Aluno::with('turma')->get();

        return view('aluno.index', compact('alunos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('turma.show');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Turma $turma)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:15',
            'livro_id' => 'nullable|integer|exists:livros,id',
            'data_emprestimo' => 'nullable|date',
            'data_devolucao' => 'nullable|date',
            'data_devolucao_prevista' => 'nullable|date',
            'status' => ['in:Sem emprestimo,No prazo,Atrasado'],
        ]);

        $turma->alunos()->create($dados);

        return redirect()
            ->route('turma.show', $turma)
            ->with('success', 'Aluno cadastrado!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $aluno = Aluno::findOrFail($id);

        return view('turma.show', compact('aluno'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Turma $turma, string $id)
    {
        $aluno = Aluno::findOrFail($id);

        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:15',
            'livro_id' => 'nullable|integer|exists:livros,id',
            'data_emprestimo' => 'nullable|date',
            'data_devolucao' => 'nullable|date',
            'data_devolucao_prevista' => 'nullable|date',
            'status' => ['in:Sem emprestimo,No prazo,Atrasado'],
        ]);

        $aluno->update($dados);

        return redirect()->route('turma.show', $turma)->with('success', 'aluno updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Turma $turma, Aluno $aluno)
    {
        $aluno = Aluno::findOrFail($aluno->id);
        $aluno->delete();

        return redirect()
            ->route('turma.show', $turma)
            ->with('success', 'Aluno removido!');
    }

    public function gerarPDF(Turma $turma, Livro $livro)
    {
        $alunos = Aluno::where('turma_id', $turma->id)
            ->with(['turma', 'livro'])
            ->orderBy('nome')
            ->get();

        $pdf = Pdf::loadView('aluno.pdf', compact('alunos', 'turma'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('alunos_'.$turma->nome_turma.'.pdf');
    }

    public function gerarPdfGeral()
    {
        $alunos = Aluno::whereHas('turma')->with(['turma', 'livro'])->orderBy('nome')->get();
        $pdf = Pdf::loadView('aluno.pdf', compact('alunos'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('alunos_geral.pdf');
    }
}
