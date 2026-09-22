@extends('layouts.app')
@section('createUser')

<div class="shadow-sm card rounded-xl bg-[#e6eaf0] dark:bg-[#464a4f]" style="width: 25rem">
                
        <div class="card-header text-center p-8 rounded-top-xl">
            <h1 class="text-2xl font-bold card-title">Criar conta</h1>
        </div>  

        <div class="card-body p-6 mt-3">

            <form method="POST" action="" class="">
                
                <div class='mb-3 rounded-xs'>
                     <label class=" block text-xs font-medium text-muted ml-10 mb-2 tracking-wide uppercase">Nome</label>
                     <div class="flex columns-2">
                       <svg xmlns="http://www.w3.org/2000/svg" width="2.5em" height="2.5em" viewBox="0 0 32 32" class="mr-1">
                            <path d="M0 0h32v32H0z" fill="none" />
                            <path fill="currentColor" d="M16 8a5 5 0 1 0 5 5a5 5 0 0 0-5-5" />
                            <path fill="currentColor" d="M16 2a14 14 0 1 0 14 14A14.016 14.016 0 0 0 16 2m7.993 22.926A5 5 0 0 0 19 20h-6a5 5 0 0 0-4.992 4.926a12 12 0 1 1 15.985 0" />
                        </svg>
                        <input type="text" placeholder="Nome do Usuario"  class=" justify-end w-full bg-card border border-border rounded-lg px-4 py-3 text-parchment text-sm placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-gold focus:border-gold transition-colors"
                        id="nome" name="nome" 
                        required>

                    </div>
                </div>
                
               <div class="mb-3 rounded-xs" >
                    <label class=" block text-xs font-medium text-muted ml-10 mb-2 tracking-wide uppercase">Email</label>
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
                    <label class="block text-xs font-medium text-muted ml-10 mb-2 tracking-wide uppercase">Senha</label>
                    <div class="flex columns-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2.5em" height="2.5em" viewBox="0 0 24 24" class="m-0.5">
                            <path d="M0 0h24v24H0z" fill="none" />
                            <path fill="currentColor" d="M12 17a2 2 0 0 1-2-2c0-1.11.89-2 2-2a2 2 0 0 1 2 2a2 2 0 0 1-2 2m6 3V10H6v10zm0-12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V10c0-1.11.89-2 2-2h1V6a5 5 0 0 1 5-5a5 5 0 0 1 5 5v2zm-6-5a3 3 0 0 0-3 3v2h6V6a3 3 0 0 0-3-3" />
                        </svg>
                        <input type="password" placeholder="Senha" 
                        class="w-full bg-card border border-border rounded-lg px-4 py-3 text-parchment text-sm placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-gold focus:border-gold transition-colors" 
                        id="password" name="password" 
                        required>
                    </div>
                </div>
                     
                <div class=" flex items-center justify-center mt-3 mb-3 rounded-sm bg-[#3D2F2F] hover:bg-[#2F3D3D] cursor-pointer "> 
                    <button type="submit" class="flex items-center justify-center  btn btn-primary py-2 text-white">REGISTRAR</button>
                </div>

                <div class="mb-3 text-sm text-blue-500 dark:text-cyan-300">
                    <a href="{{route('login')}}" class="text-decoration-none">Já tenho conta</a>
                    <!-- puxa a rota pelo nome definido -->
                </div>

            </form>
        </div>
</div>

@endsection