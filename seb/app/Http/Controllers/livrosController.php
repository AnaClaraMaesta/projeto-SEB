<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class livrosController extends Controller{


    public function create(Request $request)
    {
        $livro = new livro();
        $livro->titulo = $request->input('titulo');
        $livro->autor = $request->input('autor');
        $livro->ano_publicacao = $request->input('ano_publicacao');
        $livro->codigo_isbn = $request->input('codigo_isbn');
        $livro->save();

        return response()->json(['message' => 'Livro criado com sucesso!'], 201);

    }
}