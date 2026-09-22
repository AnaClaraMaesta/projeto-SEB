@extends('layouts.app')
@section('createUser')

<div class="shadow-sm card rounded-xl bg-[#e6eaf0] dark:bg-[#464a4f]" style="width: 25rem">
                
        <div class="card-header text-center p-8 rounded-top-xl">
            <h1 class="text-2xl font-bold card-title">Criar conta</h1>
        </div>  

        <div class="card-body p-6 mt-3">

            <form method="POST" action="" class="">
                
                <div class='mb-3 rounded-xs'>
                    <input type="text" placeholder="Nome de usuário" class='form-control'>
                </div>
                
                <div class="mb-3 rounded-xs" >
                    <input type="email" placeholder="E-mail" class="form-control" id="email" name="email" required autofocus>
                </div>

                <div class="mb-3 rounded-xs">
                    <input type="password" placeholder="Password" class="form-control" id="password" name="password" required>
                </div>
                     
                <div class="mb-3 inline-[60%] rounded-sm bg-cyan-400 ">
                    <button type="submit" class="btn btn-primary ml-[45%] ">Login</button>
                </div>

                <div class="mb-3 text-sm text-blue-500 dark:text-cyan-300">
                    <a href="{{route('layouts.login')}}" class="text-decoration-none">Já tenho conta</a>
                    <!-- puxa a rota pelo nome definido -->
                </div>

            </form>
        </div>
</div>

@endsection