@extends('layouts.app')
@section('login')


<div class="shadow-sm card rounded-xl bg-[#e6eaf0] dark:bg-[#464a4f]" style="width: 25rem">
                
        <div class="card-header text-center p-8 rounded-top-xl">
            <h1 class="text-2xl font-bold card-title">Login</h1>
        </div>  

        <div class="card-body p-6 mt-3">

            <form method="POST" action="" id="formLogin">
                @csrf

                <div class="mb-3 rounded-xs" >
                    <label class="block text-xs font-medium text-muted mb-2 tracking-wide uppercase">Email</label>
                    <input type="email" placeholder="Identificação ou E-mail" 
                    class="w-full bg-card border border-border rounded-lg px-4 py-3 text-parchment text-sm placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-gold focus:border-gold transition-colors"
                    id="email" name="email" 
                    required>
                </div>

                <div class="mb-3 rounded-xs">
                    <label class="block text-xs font-medium text-muted mb-2 tracking-wide uppercase">Senha</label>
                    <input type="password" placeholder="Senha" 
                    class="w-full bg-card border border-border rounded-lg px-4 py-3 text-parchment text-sm placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-gold focus:border-gold transition-colors" 
                    id="password" name="password" 
                    required>
                </div>
                     
                <div class="mt-3 mb-3 rounded-sm bg-[#3D2F2F] hover:bg-[#2F3D3D] cursor-pointer"> 
                    <button type="submit" class="btn btn-primary ml-[45%] py-2">ENTRAR</button>
                </div>

                <div class="mb-3 text-sm text-blue-500 dark:text-cyan-300">
                    <a href="" class="text-decoration-none">Esqueci a senha</a>
                </div>

                <div class="mb-3 text-sm">
                    <a href="{{route('usuarios.create')}}" class="text-decoration-none">Não tenho conta</a>
                    <!-- puxa a rota pelo nome definido -->
                </div>

            </form>
        </div>
</div>

@endsection
