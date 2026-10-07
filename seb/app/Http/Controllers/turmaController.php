<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turma;

class turmaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        $turmas = Turma::latest('id')->get();
        return view('turma.turmasBody', compact('turmas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {   
       $turmas = Turma::latest('id')->get();
        return view('turma.turmasBody', compact('turmas'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $dados = $request->validate([
            'nome_turma'    => 'required|string|max:255',
            'turno_turma'   => ['required', 'in:manaha,tarde,noite,integral'],
            'ano_turma'     => ['required', 'in:fundamentalI,fundamentalII,EM'],
            'materia_turma' => 'required|string|max:255',
            'deleted_at'    => 'nullable|timestamp',
        ]);
 
        Turma::create($dados);

        return response()->json([
            'success'=>'true',
            'message' => 'Turma criada com sucesso!',
            'turma' => $dados
        ], 201);
    }  

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $turma = Turma::findOrFail($id);
        return view('turma.turmasBody', compact('turma'));
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
        $turma = Turma::findOrFail($id);

        $dados = $request->validate([
            'nome_turma'    => 'required|string|max:255',
            'turno_turma'   => ['required', 'in:manha,tarde,noite,integral'],
            'ano_turma'     => ['required', 'in:fundamentalI,fundamentalII,EM'],
            'materia_turma' => 'required|string|max:255',
            'deleted_at'    => 'nullable|timestamp',
        ]);

        $turma->update($dados);

        return response()->json([
            'success'=>'true',
            'message' => 'Turma atualizada com sucesso!',
            'turma' => $dados
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $turma = Turma::findOrFail($id);
        $turma->delete();

        return response()->json([
            'success'=>'true',
            'message' => 'Turma deletada com sucesso!',
        ], 200);
    }

}
