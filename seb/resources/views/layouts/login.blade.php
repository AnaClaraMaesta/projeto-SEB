
@extends('layouts.app')
@section('login')

<div class="shadow-sm card rounded-xl" style="width: 25rem;">
                
        <div class="card-header text-center p-4 rounded-top-xl">
            <h1 class="text-2xl font-bold card-title">Login</h1>
        </div>  

        <div class="card-body p-4 mt-3 ">

            <form method="POST" action="" >
                @csrf

                <div class="mb-3">
                    <input type="email" placeholder="E-mail" class="form-control" id="email" name="email" required autofocus>
                </div>

                <div class="mb-3">
                    <input type="password" placeholder="Password" class="form-control" id="password" name="password" required>
                </div>
                     
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </div>

                <div class="mb-3 text-sm text-blue-500 dark:text-cyan-300">
                    <a href="#" class="text-decoration-none">Esqueci a senha</a>
                </div>

                <div class="mb-3 text-sm">
                    <a href="{{route('usuarios.cadastro')}}" class="text-decoration-none">Não tenho conta</a>
                    <!-- puxa a rota pelo nome definido -->
                </div>

            </form>
        </div>
</div>

@endsection
