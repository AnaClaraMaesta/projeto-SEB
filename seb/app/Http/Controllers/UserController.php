<?php

namespace App\Http\Controllers;

use App\Models\Emprestimo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function create()
    {
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
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'name.regex' => 'O nome deve conter apenas letras e espaços válidos (sem símbolos ou acentos isolados).',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Por favor, insira um endereço de e-mail válido.',
            'email.unique' => 'Já existe uma conta com esse e-mail.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha precisa ter pelo menos 8 caracteres.',
        ]);

        $user = User::create($dados);
        Auth::login($user);
        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')
            ->with('status', 'Enviamos um link de verificação para o seu e-mail.');
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
            'foto_url' => ['nullable', 'url', 'max:2048'],
            'password' => 'nullable|string|min:8',
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'name.regex' => 'O nome deve conter apenas letras e espaços válidos (sem símbolos ou acentos isolados).',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Por favor, insira um endereço de e-mail válido.',
            'email.unique' => 'Já existe uma conta com esse e-mail.',
            'foto_url.url' => 'Informe uma URL válida para a foto de perfil.',
            'password.min' => 'A senha precisa ter pelo menos 8 caracteres.',
        ]);

        if (blank($dados['password'] ?? null)) {
            unset($dados['password']);
        }

        $emailMudou = $dados['email'] !== $user->email;

        $user->update($dados);

        // Ao trocar o e-mail, a conta precisa ser verificada novamente.
        if ($emailMudou) {
            $user->forceFill(['email_verified_at' => null])->save();
            $user->sendEmailVerificationNotification();

            return redirect()->route('verification.notice')
                ->with('status', 'E-mail atualizado. Enviamos um novo link de verificação.');
        }

        return redirect()->route('usuarios.edit', $user->id)
            ->with('status', 'Dados atualizados com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_if(Auth::id() !== (int) $id, 403);

        $user = User::findOrFail($id);

        // Contas com empréstimos em aberto não podem ser removidas.
        $possuiEmprestimoAtivo = Emprestimo::withoutGlobalScope('user_turmas')
            ->whereHas('aluno.turma', fn ($query) => $query->where('user_id', $user->id))
            ->whereNull('data_devolucao')
            ->exists();

        if ($possuiEmprestimoAtivo) {
            return back()->with('error', 'Sua conta possui empréstimos em aberto e não pode ser excluída.');
        }

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $user->delete();

        return redirect()->route('login')->with('success', 'Conta excluída com sucesso.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
