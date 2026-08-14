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
                    <input type="email" placeholder="Identificação ou E-mail" class="form-control" id="email" name="email" required autofocus>
                </div>

                <div class="mb-3 rounded-xs">
                    <input type="password" placeholder="Senha" class="form-control" id="password" name="password" required>
                </div>
                     
                <div class="mb-3 inline-[60%] rounded-sm bg-cyan-400 "> 
                    <button type="submit" class="btn btn-primary ml-[45%] ">ENTRAR</button>
                </div>

                <div class="mb-3 text-sm text-blue-500 dark:text-cyan-300">
                    <a href="#" class="text-decoration-none">Esqueci a senha</a>
                </div>

                <div class="mb-3 text-sm">
                    <a href="{{route('usuarios.create')}}" class="text-decoration-none">Não tenho conta</a>
                    <!-- puxa a rota pelo nome definido -->
                </div>

            </form>
        </div>
</div>

@endsection
