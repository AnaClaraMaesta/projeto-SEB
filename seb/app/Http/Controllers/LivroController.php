<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class LivroController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search_livros');

        $livros = $this->livrosOrdenados($request);

        return view('livro.index', compact('livros', 'search'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate($this->rules());

        Livro::create($dados);

        return redirect()->route('livro.index')->with('success', 'Livro cadastrado com sucesso!');
    }

    public function update(Request $request, Livro $livro)
    {
        $dados = $request->validate($this->rules($livro));

        $livro->update($dados);

        return redirect()->route('livro.index')->with('success', 'Livro atualizado com sucesso!');
    }

    public function destroy(Livro $livro)
    {
        // Um livro com empréstimo em aberto não pode ser removido do acervo.
        // O livro é compartilhado, então verificamos todos os usuários (sem o global scope).
        if ($livro->emprestimo()->withoutGlobalScope('user_turmas')->ativos()->exists()) {
            return redirect()->route('livro.index')
                ->with('error', 'Este livro possui empréstimo ativo e não pode ser removido.');
        }

        $livro->delete();

        return redirect()->route('livro.index')->with('success', 'Livro removido com sucesso!');
    }

    public function gerarPDF(Request $request)
    {
        $livros = $this->livrosOrdenados($request);
        $pdf = Pdf::loadView('livro.pdf', compact('livros'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('relatorio_livros.pdf');
    }

    /**
     * Filtra por título, autor, código ou ano e ordena numericamente pelo
     * código (12 < 50 < 102), sem depender de CAST específico de banco.
     * Funciona em PostgreSQL e SQLite.
     */
    private function livrosOrdenados(Request $request): Collection
    {
        $search = $request->input('search_livros');

        return Livro::when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->whereLike('titulo', "%{$search}%", caseSensitive: false)
                    ->orWhereLike('autor', "%{$search}%", caseSensitive: false)
                    ->orWhereRaw('CAST(codigo AS TEXT) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('CAST(ano_publicacao AS TEXT) LIKE ?', ["%{$search}%"]);
            });
        })
            ->get()
            ->sortBy('codigo', SORT_NATURAL)
            ->values();
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(?Livro $livro = null): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'ano_publicacao' => 'required|integer|min:1|max:2100',
            'codigo' => [
                'required',
                'string',
                'max:6',
                'regex:/^\d+$/',
                Rule::unique('livros', 'codigo')->ignore($livro),
            ],
            'estoque' => 'required|integer|min:0',
        ];
    }
}
