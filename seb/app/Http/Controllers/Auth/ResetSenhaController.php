<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetSenhaController extends Controller
{
    public function showResetForm(Request $request, ?string $token = null)
    {
        return view('auth.reset-password')->with([
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetSenha(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ], [
            'token.required' => 'O link de redefinição é inválido.',
            'email.required' => 'O campo de e-mail é obrigatório.',
            'email.email' => 'Digite um formato de e-mail válido.',
            'password.required' => 'O campo de senha é obrigatório.',
            'password.confirmed' => 'A confirmação de senha não corresponde.',
            'password.min' => 'A senha precisa ter pelo menos 8 caracteres.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                $user->forceFill([
                    'password' => $password, // O cast 'hashed' cuida da criptografia
                    'remember_token' => Str::random(60),
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Senha redefinida com sucesso!')
            : back()->withErrors(['email' => 'Não foi possível redefinir a senha. Verifique o link e tente novamente.']);
    }
}
