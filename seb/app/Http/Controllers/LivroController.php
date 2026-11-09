<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class LivroController extends Controller
{
    public function index()
    {
        $livros = $this->livrosOrdenados();

        return view('livro.index', compact('livros'));
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

    public function gerarPDF()
    {
        $livros = $this->livrosOrdenados();
        $pdf = Pdf::loadView('livro.pdf', compact('livros'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('relatorio_livros.pdf');
    }

    /**
     * Ordena numericamente pelo código (12 < 50 < 102), sem depender
     * de CAST específico de banco. Funciona em PostgreSQL e SQLite.
     */
    private function livrosOrdenados(): Collection
    {
        return Livro::query()
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
                'integer',
                'min:1',
                Rule::unique('livros', 'codigo')->ignore($livro),
            ],
            'estoque' => 'required|integer|min:0',
        ];
    }
}
