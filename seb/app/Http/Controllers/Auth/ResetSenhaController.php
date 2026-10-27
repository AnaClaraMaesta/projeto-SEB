<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Symfony\Component\VarDumper\Caster\RedisCaster;

class ResetSenhaController extends Controller
{
    public function showResetForm(Request $request, string $token = null){
        return view('auth.reset-password')->with([
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function resetSenha(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'), 
            function ($user, string $password){
                $user->forceFill([
                    'password' => $password,
                    'remember_token' =>Str::random(60)
                    ])->save();
                
                 event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
        ? redirect()->route('reset.confirmation')
         : back()->withErrors(['email' => __($status)]);;
    }

}
