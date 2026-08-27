@extends('layouts.app')

@section('dashboard')

<header class="grid grid-cols-3 bg-[#3D2F2F] text-white dark:bg-[#464a4f] shadow-md">

    <div class="justify-start">
        <div class="p-3">
            <img src="https://placehold.co/40x40" alt="logo">
        </div>
    </div>

    <div class="flex justify-center gap-2">
        <div  x-data="{ open_alunos: false}" class="relative inline-block text-left">
        
            <button @click="open_alunos = !open_alunos" class="btn cursor-pointer p-3">
                ALUNOS
                
                <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open_alunos}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>

            </button>
            <div 
                x-show="open_alunos" 
                @click.away="open_alunos = false"
                x-transition
                class="absolute top-full z-50 w-28 rounded-md shadow-lg overflow-hidden bg-[#3D2F2F]"
            >
                <div class="p-0.5">
                    <button class="btn cursor-pointer border-t-2 border-gray-500">
                        CADASTRAR
                    </button> <!-- dentro de estoque vai ter a opção de cadastrar e excluir além de emitir relatórios também -->
                </div>
                <div class="p-0.5">
                    <button class="btn cursor-pointerborder-t-2 border-gray-500">
                        CONSULTAR
                    </button>
                </div>
            </div>
        </div>

        <div x-data="{ open_livros: false }" class="relative inline-block text-left">

            <button @click="open_livros = !open_livros" class="btn cursor-pointer p-3">
                LIVROS  
                
                <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open_livros}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            
            </button>

            <div
                x-show="open_livros" 
                @click.away="open_livros = false"
                x-transition
                class="absolute top-full z-50 w-28 rounded-md shadow-lg overflow-hidden bg-[#3D2F2F]"
            >
                <div class="p-0.5">
                    <button class="btn cursor-pointer border-t-2 border-gray-500">
                        CADASTRAR
                    </button> <!-- dentro de estoque vai ter a opção de cadastrar e excluir além de emitir relatórios também -->
                </div>
                <div class="p-0.5">
                    <button class="btn cursor-pointerborder-t-2 border-gray-500">
                        EMPRESTAR
                    </button>
                </div>
            </div>
        </div>

    </div>

    <div class="flex justify-end">
        <div x-data="{ open_user: false }" class="relative inline-block text-left" >
            <button @click="open_user = !open_user" class="btn cursor-pointer p-3">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>

                <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open_user}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>

            </button>

            <div 
                x-show="open_user"
                @click.away="open_user = false"
                x-transition
                class="absolute top-full z-50 w-28 rounded-md shadow-lg overflow-hidden bg-[#3D2F2F]"
            >
                <div class="p-0.5">
                    <button class="btn cursor-pointerborder-t-2 border-gray-500">
                        EDITAR
                    </button>
                </div>
            </div>
        </div>
    </div>

    
        

        
</header>


@endsection