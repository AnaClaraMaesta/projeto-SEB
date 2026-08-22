@extends('layouts.app')

@section('dashboard')

<header class="bg-[#3D2F2F] text-white dark:bg-[#464a4f] shadow-md">
    <div  x-data="{ open: false}" class="relative inline-block text-left">

        <button @click="open = !open" class="btn cursor-pointer hover:bg-[#3D2F2F] p-2.5">
            ALUNOS
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <button class="btn cursor-pointer hover:bg-[#3D2F2F] p-2.5">
            LIVROS  
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div x-show="open" x-transition class=" border-t-2 border-yellow-500">
            <div class="">
                <button class="btn cursor-pointer hover:bg-[#2F3D3D] w-full dark:hover:bg-[#3D2F2F]">
                    CADASTRAR
                </button> <!-- dentro de estoque vai ter a opção de cadastrar e excluir além de emitir relatórios também -->
            </div>
            <div class="">
                <button class="btn cursor-pointer hover:bg-[#2F3D3D] w-full dark:hover:bg-[#3D2F2F]">
                    EMPRESTAR
                </button>
            </div>
        </div>

    </div>
</header>


@endsection