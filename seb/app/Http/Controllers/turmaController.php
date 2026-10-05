<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turma;

class turmaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $turmas = Turma::all();
        return view('turma.turmasBody', compact('turmas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    
    public function create(Request $request)
    {
    
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
        ]);
 
        Turma::create($dados);

        return redirect()->route('turmas')->with('Sucesso', 'Turma criada com sucesso');
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
