<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\aluno;
use App\Models\Turma;

class alunoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'status' => ['nullable', 'in:no prazo,atrasado'],
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
            'status' => ['nullable', 'in:no prazo,atrasado'],
        ]);

        $aluno->update($dados);
        return redirect()->route('turma.show', $turma)->with('success', 'aluno updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Turma $turma, Aluno $aluno)
    {
        abort_if($aluno->turma_id !== $turma->id, 404);        
        $aluno->delete();
        
        return redirect()
        ->route('turma.show', $turma)
        ->with('success', 'Aluno removido!');
    }
}
