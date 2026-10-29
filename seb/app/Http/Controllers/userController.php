<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class userController extends Controller
{
    public function create(Request $request){
        return view('usuario.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ],[
            'email.unique'   => 'Já existe uma conta com esse e-mail.',
            'password.min'   => 'A senha precisa ter pelo menos 8 caracteres.',
        ]);

        $user = User::create($dados);
        Auth::login($user);
        $user -> sendEmailVerificationNotification();
        return redirect()->route('verification.notice')->with('necessaria autenticação de email');


    }

   public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       //
    }
}
