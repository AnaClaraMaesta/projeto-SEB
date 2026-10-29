@extends('layouts.app')
@section('createUser')


@push('scripts')
    @if ($errors->any())
        <script>
            Swal.fire({ icon: 'error', title: 'Não foi possível criar a conta', text: 'Verifique os campos abaixo.' });
        </script>
    @endif
@endpush



<div class="shadow-sm card rounded-xl bg-[#e6eaf0] dark:bg-[#464a4f]" style="width: 25rem">
                
        <div class="card-header text-center p-8 rounded-top-xl">
            <h1 class="text-2xl font-bold card-title">Criar conta</h1>
        </div>  

        <div class="card-body p-6 mt-3">

            <form method="POST" action="{{ route('usuarios.store') }}" class="">
                @csrf
                
                <div class='mb-3 rounded-xs'>
                     <label class=" block text-xs font-medium text-muted ml-10 mb-2 tracking-wide uppercase">Nome</label>
                     <div class="flex columns-2">
                       <svg xmlns="http://www.w3.org/2000/svg" width="2.5em" height="2.5em" viewBox="0 0 32 32" class="mr-1">
                            <path d="M0 0h32v32H0z" fill="none" />
                            <path fill="currentColor" d="M16 8a5 5 0 1 0 5 5a5 5 0 0 0-5-5" />
                            <path fill="currentColor" d="M16 2a14 14 0 1 0 14 14A14.016 14.016 0 0 0 16 2m7.993 22.926A5 5 0 0 0 19 20h-6a5 5 0 0 0-4.992 4.926a12 12 0 1 1 15.985 0" />
                        </svg>

                        <div class="w-full">
                            <input type="text" placeholder="Nome do Usuario"  class=" justify-end w-full bg-card border @error('name') border-red-500 @else border-border @enderror rounded-lg px-4 py-3 text-parchment text-sm placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-gold focus:border-gold transition-colors"
                            id="nome" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="text-red-500 text-xs ml-1 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>
                
               <div class="mb-3 rounded-xs" >
                    <label class=" block text-xs font-medium text-muted ml-10 mb-2 tracking-wide uppercase">Email</label>
                    <div class="flex columns-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2.5em" height="2.5em" viewBox="0 0 24 24" class="mr-1" >
                            <path d="M0 0h24v24H0z" fill="none" />
                            <path fill="currentColor" d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2zm-2 0l-8 5l-8-5zm0 12H4V8l8 5l8-5z" />
                        </svg>
                        <div class="w-full">
                            <input type="email" placeholder="E-mail" 
                                class=" justify-end w-full bg-card border @error('email') border-red-500 @else border-border @enderror rounded-lg px-4 py-3 text-parchment text-sm placeholder:text-muted/60 focus:outline-none focus:ring-1 focus:ring-gold focus:border-gold transition-colors"
                                id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                <span class="text-red-500 text-xs ml-1 mt-1 block">{{ $message }}</span>
                                @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 rounded-xs" x-data="{valor: '', minimo: 8,showPassword: false}">

                    <label for="password" class="block text-xs font-medium text-muted ml-10 mb-2 tracking-wide uppercase">
                        Senha
                    </label>

                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2.5em" height="2.5em" viewBox="0 0 24 24" class="m-0.5">
                            <path d="M0 0h24v24H0z" fill="none" />
                            <path fill="currentColor" d="M12 17a2 2 0 0 1-2-2c0-1.11.89-2 2-2a2 2 0 0 1 2 2a2 2 0 0 1-2 2m6 3V10H6v10zm0-12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V10c0-1.11.89-2 2-2h1V6a5 5 0 0 1 5-5a5 5 0 0 1 5 5v2zm-6-5a3 3 0 0 0-3 3v2h6V6a3 3 0 0 0-3-3" />
                        </svg>
                        <div class="relative w-full">
                            <input :type="showPassword ? 'text' : 'password'" placeholder="Senha" 
                            id="password" name="password" 
                            required
                            x-model="valor"
                            class="w-full border @error('password') border-red-500 @else border-border @enderror rounded-lg px-4 py-3 text-parchment text-sm placeholder:text-muted/60 focus:outline-none focus:ring-1 transition-colors"
                            :class="{
                                'bg-card border-border focus:ring-gold focus:border-gold': valor.length === 0,
                                'bg-red-500/10 border-red-500 focus:ring-red-500': valor.length > 0 && valor.length < minimo,
                                'bg-green-500/10 border-green-500 focus:ring-green-500': valor.length >= minimo
                            }">
                            <button type="button" @click="showPassword = !showPassword" class="absolute top-3 right-0 flex items-center pr-3 text-muted hover:text-parchment focus:outline-none">
                                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>

                            @error('password')
                                <span class="text-red-500 text-xs ml-1 mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        
                    </div>                    
                </div>
                     
                <div class=" flex items-center justify-center mt-3 mb-3 rounded-sm bg-[#3D2F2F] hover:bg-[#2F3D3D] cursor-pointer" onsubmit="showErro(event)"> 
                    <button type="submit" class="flex items-center justify-center  btn btn-primary py-2 text-white">
                        REGISTRAR
                    </button>
                </div>
            </form>
             <div class="mb-3 text-sm text-blue-500 dark:text-cyan-300">
                    <a href="{{route('login')}}" class="text-decoration-none">Já tenho conta</a>
                </div>
        </div>
</div>
@endsection