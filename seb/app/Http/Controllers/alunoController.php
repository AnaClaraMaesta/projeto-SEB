<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aluno;

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
    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome_aluno' => 'required|string|max:255',
            'telefone_aluno' => 'required|string|max:15',
            'endereco_aluno' => 'required|string|max:255',
            'livro_id' => 'required|integer|exists:livros,id',
            'turma_id' => 'required|integer|exists:turmas,id',
            'data_emprestimo' => 'nulable|date',
            'data_devolucao' => 'nullable|date',
            'data_devolucao_prevista' => 'nulable|date',
            'status' => ['required', 'in:no prazo,atrasado'],
        ]);

        Aluno::create($dados);
        return redirect()->route('alunos')->with('sucesso', 'Aluno criado');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
