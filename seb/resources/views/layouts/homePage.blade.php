@extends('layouts.app')

@section('dashboard')

<header class="bg-[#e6eaf0] dark:bg-[#464a4f] shadow-md">
    <div class="relative inline-block text-left">

        <button id="dropdownbutton" class="btn cursor-pointer hover:bg-[#3D2F2F] p-2.5 m-3">
            Alunos
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <button id="dropdownbutton" class="btn cursor-pointer hover:bg-[#3D2F2F] p-2.5 m-3">
            Livros
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        
        <!-- x-data gerencia o estado (aberto ou fechado) do item -->
        <div x-data="{ aberto: false }" class="border border-red-500 rounded mb-2">
                <!-- Botão que inverte o estado ao clicar -->
            <button @click="aberto = !aberto" class="w-full text-left p-4  flex justify-between">
                <span>Título do Accordion</span>
                <span x-text="aberto ? '-' : '+'"></span>
            </button>

            <!-- Conteúdo que só aparece se 'aberto' for true -->
          <div x-show="aberto" x-transition class="p-4 border-t border-yellow-500">
                Este conteúdo agora abre e fecha perfeitamente!
          </div>
        </div>

        
        
        
        <div class="hidden absolute">
            <div class="py-2">
                <a href="">Estoque</a> <!-- dentro de estoque vai ter a opção de cadastrar e excluir além de emitir relatórios também -->
                <a href="">Emprestar</a> 
            </div>
        </div>
        

    </div>
</header>


@endsection