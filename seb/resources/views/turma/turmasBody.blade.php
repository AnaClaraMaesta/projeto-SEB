@extends('turma.turmasPage')
@section('turmasBody')

<div class="bg-white dark:bg-[#24272b] text-black min-h-screen p-3 dark:bg-[#24272b] dark:text-white">
    <div class="m-3 gap-2 flex justify-between" x-data="{ add_turma: false }" @keydown.escape.window="add_turma = false">
        <button
            @click="add_turma = !add_turma" 
            class="btn cursor-pointer text-white bg-[#3D2F2F] hover:bg-[#2F3D3D] px-4 py-2 rounded-md whitespace-nowrap">
            Adicionar turma
        </button>

        <form class="max-w-2xl w-full">
            <div x-data="{open_filter:false}" @click.away="open_filter = false" class="relative flex rounded-md bg-[#3D2F2F] text-white">
                <button @click="open_filter = !open_filter" type="button" class="inline-flex items-center shrink-0 z-10 text-body bg-[#3D2F2F] box-border border cursor-pointer hover:bg-[#2F3D3D] hover:text-heading focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-s-base text-sm px-4 py-2.5 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600 hover:text-gray-900 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </button>
                
                <div x-show="open_filter" class="absolute left-0 top-full mt-1 z-20 w-44 shadow-lg bg-[#3D2F2F] rounded-base shadow-lg w-44">
                    <ul class="p-2 text-sm text-body font-medium" aria-labelledby="dropdown-button">
                        <li>
                            <a href="#" class="block p-2 hover:bg-[#2F3D3D] hover:text-heading rounded-md">Manhã</a>
                        </li>   
                        <li>
                            <a href="#" class="block p-2 hover:bg-[#2F3D3D] hover:text-heading rounded-md">Tarde</a>
                        </li>
                        <li>
                            <a href="#" class="block p-2 hover:bg-[#2F3D3D] hover:text-heading rounded-md">Noite</a>
                        </li>
                        <li>
                            <a href="#" class="block p-2 hover:bg-[#2F3D3D] hover:text-heading rounded-md">Integral</a>
                        </li>
                    </ul>
                </div>

                <input type="search" class="px-3 py-2.5 bg-neutral-secondary-medium border text-heading text-sm focus:ring-brand focus:border-brand block w-full placeholder:text-body" placeholder="Search for products" required>
                <button type="button" class="inline-flex items-center box-border border text-white bg-[#3D2F2F] box-border border cursor-pointer hover:bg-[#2F3D3D] box-border border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-e-base text-sm px-4 py-2.5 focus:outline-none">
                <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/></svg>
                    Buscar
                </button>
            </div>
        </form>
            
        <div 
            x-show="add_turma" 
            x-transition.opacity
            x-cloak
            @click.self="add_turma = false"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
        >
            <div class="w-[64vw] h-[90vh] flex flex-col rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Nova turma</h2>
                    <button type="button" @click="add_turma = false" class="text-2xl cursor-pointer">&times;</button>
                </div>

                <form action="{{ route('turma.store') }}" method="POST" class="flex flex-col flex-1 min-h-0">
                    @csrf

                    <div class="flex flex-col gap-1 p-1.5">
                        <label for="nome_turma" class="font-bold">Nome da turma:</label>
                        <input type="text" name="nome_turma" id="nome_turma"
                            class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                    </div>

                    <div class="flex flex-col gap-1 p-1.5">
                        <label for="turno_turma" class="font-bold">Turno:</label>
                        <select name="turno_turma" id="turno_turma" class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                            <option value="" class="text-black">Selecione o turno</option>
                            <option value="manha" class="text-black">Manhã</option>
                            <option value="tarde" class="text-black">Tarde</option>
                            <option value="noite" class="text-black">Noite</option>
                            <option value="integral" class="text-black">Integral</option>
                        </select>
                    </div>
                        
                    <div class="flex flex-col gap-1 p-1.5">
                        <label for="ano_turma" class="font-bold">Nível:</label>
                        <select name="ano_turma" id="ano_turma" class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                            <option value="" class="text-black">Selecione o nível</option>
                            <option value="fundamentali" class="text-black">Fundamental I</option>
                            <option value="fundamentalii" class="text-black">Fundamental II</option>
                            <option value="em" class="text-black">Ensino Médio</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1 p-1.5">
                        <label for="materia_turma" class="font-bold">Matéria:</label>
                        <input type="text" name="materia_turma" id="materia_turma" class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                    </div>

                    <div class="flex justify-end p-1.5">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Criar turma
                        </button>
                    </div>

                        {{-- <div>
                            <select name="professor_turma" id="professor_turma" class="rounded-md text-white">
                                <option value="">Selecione um professor</option>
                                <option value="professor-1">Professor 1</option>
                                <option value="professor-2">Professor 2</option>
                                <option value="professor-3">Professor 3</option>
                            </select>
                        </div> --}}

                </form>

            </div>
        </div>
    </div>

    <div class="m-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @forelse ($turmas as $turmas)
        <div class="rounded-lg overflow-hidden shadow-lg bg-[#3D2F2F] dark:bg-[#464a4f] text-white flex flex-col">

            <div class="p-4 border-b border-white/10">
                <h2 class="text-lg font-bold">{{ $turmas->nome_turma }}</h2>
            </div>

            <div class="p-4 flex-1">
                <p class="text-gray-300">{{ $turmas->materia_turma }}</p>
                <p class="text-gray-300">{{ $turmas->ano_turma }}</p>
                <p class="text-gray-300">{{ $turmas->turno_turma }}</p>
            </div>

            <div class="p-4 flex justify-end">
                <a href="{{ route('turma.dashboard', ['turma' => $turmas->id]) }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Acessar
                </a>
            </div>

        </div>


        @empty
        <div class="col-span-full flex flex-col items-center justify-center text-center py-16 w-full">
            <svg xmlns="http://w3.org" fill="currentColor" class="size-16 mb-3 text-gray-400 dark:text-gray-500" viewBox="0 0 16 16">
                <path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5zm13-3H1v2h14zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
            </svg>
            <p class="text-lg font-medium text-gray-500 dark:text-gray-400">Nenhuma turma criada</p>
        </div>                            
        

        @endforelse
    </div>  

</div>

@endsection