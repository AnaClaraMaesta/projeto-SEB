<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class EsqueciSenhaController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.Esqueci-senha');
    }

    public function enviarLinkResetEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'O campo de e-mail é obrigatório.',
            'email.email' => 'Digite um formato de e-mail válido.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_THROTTLED) {
            return back()->withErrors([
                'email' => 'Aguarde alguns instantes antes de solicitar outro link.',
            ]);
        }

        // A mesma resposta é exibida exista ou não a conta, para não revelar
        // quais e-mails estão cadastrados.
        return back()->with('status', 'Se o e-mail estiver cadastrado, enviaremos um link para redefinir a senha.');
    }
}
