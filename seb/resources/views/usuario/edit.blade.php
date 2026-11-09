@extends('layouts.app')
@section('edit-user')

<div class="flex h-screen"
    x-data = "{get actionEdit() = { return `${this.baseUrl}/${this.turmaId}/aluno`; }}">
    @include('layouts.navBar')
   
    <a href="{{ route('turma.index') }}" class="m-3 inline-block text-sm text-gray-500 dark:text-gray-400 hover:underline">
            &larr; Voltar
    </a>
   
    <main class="flex-1 flex items-center justify-center p-5 overflow-y-auto" x-data="{loading: false}">
        <div class="w-[64vw] max-w-full max-h-full flex flex-col rounded-xl overflow-y-auto bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">

            <form method="POST" action="{{ route('usuarios.update', $user->id) }}" class="space-y-4" @submit="loading = true; axios.post(actionEdit,new FormData($event.target)).then(()=> {loading = false})">
                @csrf
                @method('PUT')
                
                @if ($errors->any())
                    <div class="text-sm text-red-600">{{ $errors->first() }}</div>
                @endif
                
                <div>
                    <label for="name" class="block text-sm font-medium mb-1">Nome</label>
                    <input type="text" id="name" name="name"
                    value="{{ old('name', $user->name) }}" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium mb-1">Email</label>
                    <input type="text" id="email" name="email"
                    value="{{ old('email', $user->email) }}" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2">
                </div>
                

                <div x-data="{valor: '', minimo: 8,showPassword: false}">
                    
                    <label for="senha" class="block text-sm font-medium mb-1">Nova senha <span class="text-white/40 font-normal">(opcional)</span></label>

                    <div class="relative w-full">
                    <input :type="showPassword ? 'text' : 'password'" placeholder="Senha" 
                        id="password" name="password" 
                        x-model="valor"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2"
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

                <div class="flex justify-end p-1.5" class="w-full max-w-lg rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
                        
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    :disabled="loading"
                    :class="{ 'opacity-50 cursor-not-allowed': loading }"
                    >
                        <span  x-text="loading ? 'Salvando...' : 'Salvar'"></span>

                    </button>
                </div>

            </form>
        </div>

    </div>

</div>

@endsection