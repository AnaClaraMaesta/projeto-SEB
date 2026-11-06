<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;
use App\Models\Turma;
use App\Models\Livro;
use Barryvdh\DomPDF\Facade\Pdf;

class AlunoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Turma $turma)
    {
        $alunos = Aluno::with('turma')->get();        
        return view('aluno.index', compact('alunos', 'turma'));
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
        $aluno = aluno::findOrFail($id);
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
        $aluno = aluno::findOrFail($id);    

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
        $aluno = aluno::findOrFail($aluno->id);
        $aluno->delete();
        
        return redirect()
        ->route('turma.show', $turma)
        ->with('success', 'Aluno removido!');
    }

    public function gerarPDF(Turma $turma, Livro $livro)
    {
        $alunos = aluno::where('turma_id', $turma->id)
        ->with(['turma', 'livro'])
        ->orderBy('nome')
        ->get();

        $pdf = Pdf::loadView('aluno.pdf', compact('alunos', 'turma'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('alunos_' . $turma->id . '.pdf');
    }

    public function gerarPdfGeral(){
        $alunos = aluno::whereHas('turma')->with(['turma', 'livro'])->orderBy('nome')->get();
        $pdf = Pdf::loadView('aluno.pdf', compact('alunos'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('alunos_geral.pdf');
    }
}
