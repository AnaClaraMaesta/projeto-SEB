<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{   
    public function index(Request $request)
    {
        return view('layouts.login');
    }

    public function store(Request $request){
        
    $dados = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($dados, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('turmas');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

}
