<?php

namespace App\Http\Controllers\Auth;
use App\Services\Login;
use Illuminate\Http\Request;

class loginController
{   
    public function index(Request $request)
    {
        return view('layouts.login');
    }

    public function store(Request $request){
        
    $dados = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'senha' => 'required|string|min:8',
        ]);

        Login::validar($dados);

        return redirect()->route('turmas')->with('success', 'Login realizado com sucesso');
    }

}
