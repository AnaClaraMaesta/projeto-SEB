@extends('layouts.app')
@section('login')


<div class="p-5">

    <div class="w-full max-w-md rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden bg-[#e6eaf0] dark:bg-[#464a4f]" style="width: 25rem">
                    
            <div class="px-8 pt-10 pb-2 text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Olá novamente</h1>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Entre com sua conta para continuar</p>
            </div>
            
            <div class="px-8 pb-8 pt-6">
                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 px-4 py-3 text-sm text-red-700 dark:text-red-300">
                        {{ $errors->first() }}
                    </div>
                @endif
    
                <form method="POST" action="{{ route('login.store') }}" id="formLogin" class="">
                    @csrf
    
                    <div class="mb-3 rounded-xs" >
                        <label class=" block text-xs font-medium text-muted ml-9 mb-2 tracking-wide uppercase">Email</label>
                        
                        <div class="flex columns-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="2.5em" height="2.5em" viewBox="0 0 24 24" class="mr-1" >
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path fill="currentColor" d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2zm-2 0l-8 5l-8-5zm0 12H4V8l8 5l8-5z" />
                            </svg>
                            <input type="email" placeholder="E-mail" 
                            class=" justify-end w-full bg-card border border-border rounded-lg px-4 py-3 text-parchment text-sm placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-gold focus:border-gold transition-colors"
                            id="email" name="email" 
                            required>
                        </div>
                    </div>
    
                    <div class="mb-3 rounded-xs">
                        <label class="block text-xs font-medium text-muted ml-9 mb-2 tracking-wide uppercase">Senha</label>
                        <div class="flex columns-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="2.5em" height="2.5em" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path fill="currentColor" d="M12 17a2 2 0 0 1-2-2c0-1.11.89-2 2-2a2 2 0 0 1 2 2a2 2 0 0 1-2 2m6 3V10H6v10zm0-12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V10c0-1.11.89-2 2-2h1V6a5 5 0 0 1 5-5a5 5 0 0 1 5 5v2zm-6-5a3 3 0 0 0-3 3v2h6V6a3 3 0 0 0-3-3" />
                            </svg>
                            
                            <input type="password" placeholder="Senha" 
                            class="w-full bg-card border border-border rounded-lg px-4 py-3 text-parchment text-sm placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-gold focus:border-gold transition-colors" 
                            id="password" name="password" 
                            required>
                        </div>
                    </div>
                         
                     <div class="w-full py-4 rounded-lg text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2"> 
                        <button type="submit" class="flex items-center rounded-sm w-full justify-center bg-[#3D2F2F] hover:bg-[#2F3D3D] cursor-pointer btn py-2 text-white">ENTRAR</button>
                    </div>
                </form>
    
                <div class="mb-3 text-sm text-blue-500 dark:text-cyan-300">
                    <a href="{{route('password.request')}}" class="text-decoration-none">Esqueci a senha</a>
                </div>
    
                <div class="mb-3 text-sm">
                    <a href="{{route('usuarios.create')}}" class="text-decoration-none">Não tenho conta</a>
                </div>
    
            </div>
    </div>

</div>

@endsection
