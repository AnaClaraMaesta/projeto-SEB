@extends('layouts.app')
@section('reset-password')

<div class="shadow-sm card rounded-xl bg-[#e6eaf0] dark:bg-[#464a4f]" style="width: 25rem">

    <div class="card-body p-6 mt-3">

    @if (session('status'))
    <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
        {{ session('status') }}
    </div>
    @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <!-- Token necessário para o Laravel validar a requisição -->
            <input type="hidden" name="token" value="{{ $token }}">

             <div class="mb-3 rounded-xs" >
                            <label class=" block text-xs font-medium text-muted ml-9 mb-2 tracking-wide uppercase">Email</label>
                            <div class="flex columns-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="2.5em" height="2.5em" viewBox="0 0 24 24" class="mr-1" >
                                    <path d="M0 0h24v24H0z" fill="none" />
                                    <path fill="currentColor" d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2zm-2 0l-8 5l-8-5zm0 12H4V8l8 5l8-5z" />
                                </svg>
                                <input type="email" placeholder="E-mail" 
                                class=" justify-end w-full bg-card border border-border rounded-lg px-4 py-3 text-parchment text-sm placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-gold focus:border-gold transition-colors"
                                id="email" name="email" value="{{ request('email') }}"
                                required>
                            </div>
                        </div>

             <div class="mb-3 rounded-xs">
                            <label class="block text-xs font-medium text-muted ml-9 mb-2 tracking-wide uppercase">Nova Senha</label>
                            <div class="flex columns-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="2.5em" height="2.5em" viewBox="0 0 24 24">
                                    <path d="M0 0h24v24H0z" fill="none" />
                                    <path fill="currentColor" d="M12 17a2 2 0 0 1-2-2c0-1.11.89-2 2-2a2 2 0 0 1 2 2a2 2 0 0 1-2 2m6 3V10H6v10zm0-12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V10c0-1.11.89-2 2-2h1V6a5 5 0 0 1 5-5a5 5 0 0 1 5 5v2zm-6-5a3 3 0 0 0-3 3v2h6V6a3 3 0 0 0-3-3" />
                                </svg>
                                <input type="password" placeholder="Nova Senha" 
                                class="w-full bg-card border border-border rounded-lg px-4 py-3 text-parchment text-sm placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-gold focus:border-gold transition-colors" 
                                id="password" name="password" 
                                required>
                            </div>
                        </div>

               <div class="mb-3 rounded-xs">
                        <label class="block text-xs font-medium text-muted ml-9 mb-2 tracking-wide uppercase">Confirme a Senha</label>
                            <div class="flex columns-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="2.5em" height="2.5em" viewBox="0 0 24 24">
                                    <path d="M0 0h24v24H0z" fill="none" />
                                    <path fill="currentColor" d="M12 17a2 2 0 0 1-2-2c0-1.11.89-2 2-2a2 2 0 0 1 2 2a2 2 0 0 1-2 2m6 3V10H6v10zm0-12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V10c0-1.11.89-2 2-2h1V6a5 5 0 0 1 5-5a5 5 0 0 1 5 5v2zm-6-5a3 3 0 0 0-3 3v2h6V6a3 3 0 0 0-3-3" />
                                </svg>
                                <input type="password" placeholder="Confirme a Senha" 
                                class="w-full bg-card border border-border rounded-lg px-4 py-3 text-parchment text-sm placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-gold focus:border-gold transition-colors" 
                                id="password" name="password_confirmation" 
                                required>
                            </div>
                </div>
                <div class=" flex items-center justify-center mt-3 mb-3 rounded-sm bg-[#3D2F2F] hover:bg-[#2F3D3D] cursor-pointer "> 
                    <button type="submit" class="flex items-center justify-center  btn btn-primary py-2 text-white w-full">
                        REDEFINIR SENHA
                    </button>
                </div>
            
        </form>

    </div>

</div>
@endsection