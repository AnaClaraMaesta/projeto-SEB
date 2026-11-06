<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
            /** @var User $user */
            $user = Auth::user(); 
                if (! $user->hasVerifiedEmail()){
                    $user -> sendEmailVerificationNotification();
                    return redirect()->route('verification.notice');
                }
           return redirect()->intended(route('turmas'));
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
