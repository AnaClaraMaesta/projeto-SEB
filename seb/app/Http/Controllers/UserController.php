<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function create(){
        
        return view('usuario.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            'name' => 'required|string|max:255|regex:/^[\p{L}\s]+$/u',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ],[
            'name.required' => 'o campo nome é obrigatorio',
            'name.regex' => 'O nome deve conter apenas letras e espaços válidos (sem símbolos ou acentos isolados).',
            'email.required' => 'o campo email é obrigatorio',
            'email.email' => 'Por favor, insira um endereço de email valido',
            'email.unique'   => 'Já existe uma conta com esse e-mail.',
            'password.required' => 'o campo senha é obrigatorio',
            'password.min'   => 'A senha precisa ter pelo menos 8 caracteres.',
        ]);

        $user = User::create($dados);
        Auth::login($user);
        $user -> sendEmailVerificationNotification();
        return redirect()->route('verification.notice')->with('necessaria autenticação de email');


    }

   public function edit(string $id)
    {
        abort_if(Auth::id() !== (int) $id, 403);
        $user = User::findOrFail($id);

        return view('usuario.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(Auth::id() !== (int) $id, 403);

        $user = User::findOrFail($id);

        $dados = $request->validate([
            'name' => 'required|string|max:255|regex:/^[\p{L}\s]+$/u',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'senha' => 'nullable|string|min:8',
        ],[
            'name.required' => 'o campo nome é obrigatorio',
            'name.regex' => 'O nome deve conter apenas letras e espaços válidos (sem símbolos ou acentos isolados).',
            'email.required' => 'o campo email é obrigatorio',
            'email.email' => 'Por favor, insira um endereço de email valido',
            'email.unique'   => 'Já existe uma conta com esse e-mail.',
            'password.min'   => 'A senha precisa ter pelo menos 8 caracteres.',
        ]);


        if (empty($dados['password'])) {
            unset($dados['password']);
        }

        $user->update($dados);
        return redirect()->route('usuarios.edit', $user->id)->with('status', 'Dados atualizados.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       
        abort_if(Auth::id() !== (int) $id, 403);

        $user = User::findOrFail($id);

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $user->delete();

        return redirect()->route('login')->with('success', 'Conta deletada.');


    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
