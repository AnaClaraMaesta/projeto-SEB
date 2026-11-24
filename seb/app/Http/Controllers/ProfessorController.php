<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfessorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Professor::class);

        $search = $request->input('search');

        $professores = Professor::with('turmas')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereLike('nome', "%{$search}%", caseSensitive: false)
                        ->orWhereLike('email', "%{$search}%", caseSensitive: false)
                        ->orWhereLike('telefone', "%{$search}%", caseSensitive: false)
                        ->orWhereHas('turmas', function ($turma) use ($search) {
                            $turma->whereLike('nome_turma', "%{$search}%", caseSensitive: false);
                        });
                });
            })
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString();

        return view('professor.index', compact('professores', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Professor::class);

        $dados = $this->validatedData($request);
        $dados['user_id'] = Auth::id();

        $professor = Professor::create($dados);

        // Usado pelo atalho "+" do modal de turma (fetch/AJAX).
        if ($request->expectsJson()) {
            return response()->json([
                'id' => $professor->id,
                'nome' => $professor->nome,
                'email' => $professor->email,
                'telefone' => $professor->telefone,
            ], 201);
        }

        return redirect()->route('professor.index')->with('success', 'Professor cadastrado com sucesso!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Professor $professor)
    {
        $this->authorize('update', $professor);

        $dados = $this->validatedData($request, $professor);

        $professor->update($dados);

        return redirect()->route('professor.index')->with('success', 'Professor atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Professor $professor)
    {
        $this->authorize('delete', $professor);

        $professor->delete();

        return redirect()->route('professor.index')->with('success', 'Professor removido com sucesso!');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Professor $professor = null): array
    {
        return $request->validate([
            'nome' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('professores', 'email')->ignore($professor),
            ],
            'telefone' => 'required|string|max:11',
        ]);
    }
}
