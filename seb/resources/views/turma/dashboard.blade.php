@extends('turma.turmasPage')
@section('dashboard')

<div class="bg-[#e6eaf0] text-black min-h-screen p-3 dark:bg-[#24272b] dark:text-white">
    <div class="m-3 gap-2 flex justify-between">
        
        <div class="justify-start">

            <button
                @click="$dispatch('abrir-add-turma')"
                class="btn cursor-pointer text-white bg-[#3D2F2F] hover:bg-[#2F3D3D] px-4 py-2 rounded-md whitespace-nowrap">
                Adicionar turma
            </button>
        
            <a
            href="{{ route('turma.pdf') }}"
            class="btn cursor-pointer text-white bg-[#3D2F2F] hover:bg-[#2F3D3D] px-4 py-2 rounded-md whitespace-nowrap">
            Emitir relatório
            </a>
    </div>



        <form class="max-w-2xl w-full" method="GET" action="{{ route('turma.index') }}">
            <div x-data="{open_filter:false}" @click.away="open_filter = false" class="relative flex rounded-md bg-[#3D2F2F] text-white">
                <button @click="open_filter = !open_filter" type="button" class="inline-flex items-center shrink-0 z-10 text-body bg-[#3D2F2F] box-border border cursor-pointer hover:bg-[#2F3D3D] hover:text-heading focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-s-base text-sm px-4 py-2.5 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600 hover:text-gray-900 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </button>
                
                <div x-show="open_filter" class="absolute left-0 top-full mt-1 z-20 w-44 shadow-lg bg-[#3D2F2F] rounded-base shadow-lg w-44">
                    <select class="p-2 text-sm text-body font-medium" aria-labelledby="dropdown-button" name="turno_turma">
                            <option value="">Todos</option>
                            <option value="manha" @selected(request('turno_turma') === 'manha')>Manhã</option>
   
                            <option value="tarde" @selected(request('turno_turma') === 'tarde')>Tarde</option>

                            <option value="noite" @selected(request('turno_turma') === 'noite')>Noite</option>

                            <option value="integral" @selected(request('turno_turma') === 'integral')>Integral</option>
                    </select>
                </div>

                <input type="search" name="search" value="{{ request()->input('search') }}" class="px-3 py-2.5 bg-neutral-secondary-medium border text-heading text-sm focus:ring-brand focus:border-brand block w-full placeholder:text-body" placeholder="Search for products">
                <button type="submit" class="inline-flex items-center box-border border text-white bg-[#3D2F2F] box-border border cursor-pointer hover:bg-[#2F3D3D] box-border border border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-e-base text-sm px-4 py-2.5 focus:outline-none">
                <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/></svg>
                    Buscar
                </button>
            </div>
        </form>
    </div>    

    <div class="m-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
    @forelse ($turmas as $turma)
        <div class="relative rounded-lg overflow-hidden shadow-lg bg-[#3D2F2F] dark:bg-[#464a4f] text-white flex flex-col transition-transform duration-300 ease-out hover:scale-105">
                
            <a href="{{ route('turma.show', $turma->id) }}" class="absolute inset-0 z-0  "
                aria-label="Abrir turma {{ $turma->nome_turma }}">
            </a>

            <div class="p-4 border-b border-white/10">
                <h2 class="text-lg font-bold">{{ $turma->nome_turma }}</h2>
            </div>
        
            <div class="p-4 flex-1 transition-transform duration-300">
                        
                @if($turma->ano_turma == "fundamentali")
                    <p class="text-gray-300">Fundamental I</p>
                @endif
                @if($turma->ano_turma == "fundamentalii")
                    <p class="text-gray-300">Fundamental II</p>
                @endif
                @if($turma->ano_turma == "em")
                    <p class="text-gray-300">Ensino Médio</p>   
                @endif

            </div>

                
            {{-- rodape --}}
    
            <div class="relative z-10 p-4 flex justify-end" x-data="{ edit_turma: false }" @keydown.escape.window="edit_turma = false">
                    
                {{-- update --}}

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2"
                    @click="$dispatch('abrir-edit-turma', {
                    turma: @js($turma->only(['id', 'nome_turma', 'turno_turma', 'ano_turma']))
                })">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                    </svg>
                </button>
                    
                    {{-- deletar --}}
                                         
                    
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2"
                    @click="$dispatch('abrir-del-turma', {
                    turma: @js($turma->only(['id', 'nome_turma']))
                })">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                    </svg>
                </button>
            </div>
        </div>

        @empty
        
        <div class="col-span-full flex flex-col items-center justify-center text-center py-16 w-full">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-16 mb-3 text-gray-400 dark:text-gray-500" viewBox="0 0 16 16">
                <path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5H2zm13-3H1V2h14v1z"/>
            </svg>
            <p class="text-gray-500 dark:text-gray-400">Nenhuma turma encontrada.</p>
        </div>

        @endforelse
    
    </div>  

</div>

@if (session('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 4000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed bottom-4 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3
                   rounded-lg border border-green-500 bg-green-500/10 backdrop-blur
                   px-5 py-3 shadow-lg text-sm text-green-600 dark:text-green-400"
            role="status"
        >
            <span>{{ session('success') }}</span>
            <button type="button" @click="show = false" class="ml-2 opacity-70 hover:opacity-100" aria-label="Fechar">✕</button>
        </div>
@endif
@endsection