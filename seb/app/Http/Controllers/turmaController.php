<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Turma;
use Barryvdh\DomPDF\Facade\Pdf;


class turmaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $turmas = Turma::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('nome_turma', 'like', "%{$search}%")
                    ->orWhere('turno_turma', 'like', "%{$search}%")
                    ->orWhere('ano_turma', 'like', "%{$search}%");
            })
            ->when($request->filled('turno_turma'), function ($query) use ($request) {
                $query->where('turno_turma', 'like', "%{$request->turno_turma}%");
            })
            ->orderBy('nome_turma')
            ->paginate(15)  
            ->withQueryString();

        return view('turma.dashboard', compact('turmas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {   
       $turmas = Turma::all();
        return view('turma.turmasBody', compact('turmas'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $dados = $request->validate([
            'nome_turma'    => 'required|string|max:255',
            'turno_turma'   => ['required', 'in:manha,tarde,noite,integral'],
            'ano_turma'     => ['required', 'in:fundamentali,fundamentalii,em'],
            'deleted_at'    => 'nullable|timestamp',
            'created_at'    => 'nullable|timestamp',
            'updated_at'    => 'nullable|timestamp',
        ]);

        $dados['user_id'] = Auth::id();
        
        Turma::create($dados);

        return redirect()->route('turma.index')->with('success', 'Turma criada com sucesso!');
    }  

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $turma = Turma::findOrFail($id);
        return view('turma.show', compact('turma'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $turma = Turma::findOrFail($id);
        return view('turma.turmasBody', compact('turma'));
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
            'ano_turma'     => ['required', 'in:fundamentali,fundamentalii,em'],
        ]);

        $turma->update($dados);

        return redirect()->route('turma.index')->with('Sucesso', 'Turma atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $turma = Turma::findOrFail($id);
        $turma->delete();

        return redirect()->route('turma.index')->with('Sucesso', 'Turma excluída com sucesso!');
    }

    public function gerarPDF()
    {
        $turmas = Turma::all();
        $pdf = Pdf::loadView('turma.pdf', compact('turmas'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('relatorio_turmas.pdf');
    }

}
