@extends('layouts.app')
@section('forgot-password')

<div class="shadow-sm card rounded-xl bg-[#e6eaf0] dark:bg-[#464a4f]" style="width: 25rem">

    <div class="card-body p-6 mt-3">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="card-header text-center p-8 rounded-top-xl">
                <h1 class="text-2xl font-bold card-title">Redefinir Senha</h1>
            </div>  

            <div class="mb-3 rounded-xs" >
                 <label class=" block text-xs font-medium text-muted ml-10 mb-2 tracking-wide uppercase">Email</label>
                 <div class="flex columns-2">
                     <svg xmlns="http://www.w3.org/2000/svg" width="2.5em" height="2.5em" viewBox="0 0 24 24" class="mr-1" >
                         <path d="M0 0h24v24H0z" fill="none" />
                         <path fill="currentColor" d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2zm-2 0l-8 5l-8-5zm0 12H4V8l8 5l8-5z" />
                     </svg>
                     <input type="email" placeholder="E-mail" 
                     class=" justify-end w-full bg-card border border-border rounded-lg px-4 py-3 text-parchment text-sm placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-gold focus:border-gold transition-colors @error('email') border-red-500 @enderror"
                     id="email" name="email" 
                     value="{{ old('email') }}"
                     required>
                 </div>
            </div>
            

            @if (session('status') == 'reset-link-sent')
                <div class="mb-4 text-sm font-medium text-green-800 bg-green-100 p-3 rounded-md text-center border border-green-200">
                    Um novo link de redefinição foi enviado para o endereço de e-mail fornecido no cadastro.
                </div>
            @endif

            @error('email')
            <span style="color: #700909; font-size: 0.875rem;">{{ $message }}</span>
            @enderror

            <div class="  flex items-center justify-center mt-3 mb-3 rounded-sm bg-[#3D2F2F] hover:bg-[#2F3D3D] cursor-pointer "> 
             <button type="submit" class="flex items-center justify-center  btn btn-primary py-2 text-white w-full">
                 ENVIAR EMAIL
             </button>
            </div>
        </form>

        <div class="mb-3 text-sm text-blue-500 dark:text-cyan-300">
            <a href="{{route('login')}}" class="text-decoration-none">Lembrei a senha</a>
        </div>
    </div>
</div>

@endsection