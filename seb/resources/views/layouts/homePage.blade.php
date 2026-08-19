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

        <div class="hidden absolute">
            <div class="py-2">
                <a href="">Estoque</a> <!-- dentro de estoque vai ter a opção de cadastrar e excluir além de emitir relatórios também -->
                <a href="">Emprestar</a> 
            </div>
        </div>

    </div>
</header>


@endsection