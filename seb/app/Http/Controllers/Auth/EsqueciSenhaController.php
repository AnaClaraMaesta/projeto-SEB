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
        return view('auth.forgot-password');
    }

    public function enviarLinkResetEmail(Request $request){
            $dados = $request->validate(['email' => 'required|email']);
            $user = User::where('email', $request->email)->first();
           
        
           if ($user && is_null($user->email_verified_at)) {
                // Guarda o e-mail na sessão temporária
                session(['email_for_verification' => $user->email]);
    
                // Redireciona para a rota 'verification.notice' (email/verify)
                $user -> sendEmailVerificationNotification();
                return redirect()->route('verification.notice')
                ->with('warning', 'Seu e-mail ainda não foi verificado. Por favor, confirme seu e-mail antes de redefinir a senha.');
            }

            $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]); 
    }
}

