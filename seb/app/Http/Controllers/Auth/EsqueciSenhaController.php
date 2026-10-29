<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EsqueciSenhaController extends Controller
{
    public function showLinkRequestForm(){
        return view('auth.Esqueci-senha');
    }

    public function enviarLinkResetEmail(Request $request){
            $request->validate(['email' => 'required|email|exists:users,email'],
            ['email.exists' => 'E-mail não identificado, crie um usuário primeiro.',
             'email.required' => 'O campo de e-mail é obrigatório.',
             'email.email' => 'Digite um formato de e-mail válido.',
             ]);
           
            $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status','reset-link-sent')
            : back()->withErrors(['email' => __($status)]); 
    }
}

