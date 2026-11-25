<?php

namespace App\Http\Controllers;

use App\Models\Turma;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TurmaController extends Controller
{
    public function turmasFilter(Request $request)
    {
        $search = $request->input('search');

        return Turma::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereLike('nome_turma', "%{$search}%", caseSensitive: false)
                        ->orWhereLike('turno_turma', "%{$search}%", caseSensitive: false)
                        ->orWhereRaw('CAST(ano_turma AS TEXT) LIKE ?', ["%{$search}%"]);
                });
            })
            ->when($request->filled('turno_turma'), function ($query) use ($request) {
                $query->where('turno_turma', $request->turno_turma);
            })
            ->orderBy('nome_turma');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Turma::class);

        $search = $request->input('search');

        $turmas = $this->turmasFilter($request)
            ->with('user')
            ->paginate(15)
            ->withQueryString();

        return view('turma.dashboard', compact('turmas', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Turma::class);

        $dados = $request->validate([
            'nome_turma' => 'required|string|max:255',
            'turno_turma' => ['required', 'in:manha,tarde,noite,integral'],
            'ano_turma' => ['required', 'in:fundamentali,fundamentalii,em'],
            'cor' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $dados['user_id'] = Auth::id();

        $turma = Turma::create($dados);

        // Usado pelo atalho "+" do modal de aluno (fetch/AJAX).
        if ($request->expectsJson()) {
            return response()->json([
                'id' => $turma->id,
                'nome_turma' => $turma->nome_turma,
                'turno_turma' => $turma->turno_turma,
                'ano_turma' => $turma->ano_turma,
                'cor' => $turma->cor,
            ], 201);
        }

        return redirect()->route('turma.index')->with('success', 'Turma criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $turma = Turma::findOrFail($id);
        $this->authorize('view', $turma);

        $turma->load([
            'user',
            'alunos' => fn ($query) => $query
                ->with(['emprestimoAtivo.livro', 'livro'])
                ->comEmprestimos(),
        ]);

        return view('turma.show', compact('turma'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $turma = Turma::findOrFail($id);
        $this->authorize('update', $turma);

        $dados = $request->validate([
            'nome_turma' => 'required|string|max:255',
            'turno_turma' => ['required', 'in:manha,tarde,noite,integral'],
            'ano_turma' => ['required', 'in:fundamentali,fundamentalii,em'],
            'cor' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ]);

        $turma->update($dados);

        return redirect()->route('turma.index')->with('success', 'Turma atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $turma = Turma::findOrFail($id);
        $this->authorize('delete', $turma);

        if ($turma->alunos()
            ->whereHas('emprestimo', fn ($query) => $query->whereNull('data_devolucao'))
            ->exists()) {
            return redirect()->route('turma.index')
                ->with('error', 'Esta turma possui empréstimo em aberto e não pode ser excluída.');
        }

        $turma->delete();

        return redirect()->route('turma.index')->with('success', 'Turma excluída com sucesso!');
    }

    public function gerarPDF(Request $request)
    {
        $this->authorize('viewAny', Turma::class);

        $turmas = $this->turmasFilter($request)
            ->with(['user', 'alunos' => fn ($query) => $query->comEmprestimos()])
            ->get();

        $pdf = Pdf::loadView('turma.pdf', compact('turmas'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('relatorio_turmas.pdf');
    }
}
