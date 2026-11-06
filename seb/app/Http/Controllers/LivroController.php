<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LivroController extends Controller
{
    public function index()
    {
        $livros = Livro::orderBy('titulo')->get();

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
        $livro->delete();

        return redirect()->route('livro.index')->with('success', 'Livro removido com sucesso!');
    }

    public function gerarPDF()
    {
        $livros = Livro::orderBy('titulo')->get();
        $pdf = Pdf::loadView('livro.pdf', compact('livros'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('relatorio_livros.pdf');
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
                Rule::unique('livros', 'codigo')->ignore($livro),
            ],
            'estoque' => 'required|integer|min:0',
            'status' => ['required', 'in:Disponível,Emprestado,Indisponível'],
        ];
    }
}
