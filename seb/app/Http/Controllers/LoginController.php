<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        return view('layouts.login');
    }

    public function store(Request $request)
    {

        $dados = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Digite um endereço de e-mail válido.',
            'password.required' => 'O campo senha é obrigatório.',
        ]);

        if (Auth::attempt($dados, $request->boolean('remember'))) {
            $request->session()->regenerate();
            /** @var User $user */
            $user = Auth::user();
            if (! $user->hasVerifiedEmail()) {
                // Mantém o destino original para depois da verificação.
                if (! $request->session()->has('url.intended')) {
                    $request->session()->put('url.intended', route('turma.index'));
                }

                $user->sendEmailVerificationNotification();

                return redirect()->route('verification.notice')
                    ->with('status', 'Confirme seu e-mail para acessar o sistema.');
            }

            return redirect()->intended(route('turma.index'));
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
