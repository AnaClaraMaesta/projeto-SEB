@extends('livro.livrosPage')
@section('index')

    <div class="bg-[#e6eaf0] text-black min-h-screen p-3 dark:bg-[#24272b] dark:text-white">
        <div class="mx-3 mt-1 mb-2 flex justify-between">
            <div class="justify-start">
                <a href="{{ route('turma.index') }}" class="inline-block text-sm text-gray-500 dark:text-gray-400 hover:underline">
                    &larr; Voltar
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="mx-3 mb-2 rounded-md border border-red-500 bg-red-500/10 px-4 py-3 text-sm text-red-700 dark:text-red-400">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="mx-3 mb-3 flex flex-wrap items-center justify-between gap-2">
            <div class="flex gap-2">
                <button type="button"
                        @click="$dispatch('abrir-add-livro')"
                        class="cursor-pointer text-white bg-[#3D2F2F] hover:bg-[#2F3D3D] px-4 py-2 rounded-md whitespace-nowrap">
                    + Novo Livro
                </button>

                <a href="{{ route('livro.pdf', ['search_livros' => $search]) }}"
                    class="cursor-pointer text-white bg-[#3D2F2F] hover:bg-[#2F3D3D] px-4 py-2 rounded-md whitespace-nowrap inline-flex items-center">
                    Emitir relatório
                </a>
            </div>

            <form class="max-w-2xl w-full sm:w-auto" method="GET" action="{{ route('livro.index') }}">
                <div class="relative flex rounded-md bg-[#3D2F2F] text-white">
                    <input type="search" value="{{ $search }}" name="search_livros"
                        class="px-3 py-2.5 bg-neutral-secondary-medium border text-heading text-sm focus:ring-brand focus:border-brand block w-full placeholder:text-body"
                        placeholder="Buscar por título, autor, código ou ano">
                    <button type="submit" class="inline-flex items-center box-border border text-white bg-[#3D2F2F] cursor-pointer hover:bg-[#2F3D3D] border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-e-base text-sm px-4 py-2.5 focus:outline-none">
                        <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/></svg>
                        Buscar
                    </button>

                    @if ($search)
                        <a href="{{ route('livro.index') }}"
                            class="inline-flex items-center text-white bg-[#3D2F2F] hover:bg-[#2F3D3D] text-sm px-4 py-2.5 focus:outline-none">
                            Limpar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="m-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse ($livros as $livro)
                <div class="relative rounded-lg overflow-hidden shadow-lg bg-[#3D2F2F] dark:bg-[#464a4f] text-white flex flex-col transition-transform duration-300 ease-out hover:scale-105">

                    {{-- Capa do livro --}}
                    <div class="relative h-48 w-full overflow-hidden bg-black/25">
                        @if ($livro->capa_url)
                            <img src="{{ $livro->capa_url }}" alt="Capa de {{ $livro->titulo }}"
                                class="h-full w-full object-cover" loading="lazy"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                            <div class="hidden absolute inset-0 items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-10 h-10 text-white/30" viewBox="0 0 16 16">
                                    <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                                </svg>
                            </div>
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-10 h-10 text-white/30" viewBox="0 0 16 16">
                                    <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                                </svg>
                            </div>
                        @endif

                        {{-- Status --}}
                        <span class="absolute top-2 right-2">
                            @if ($livro->status === 'Disponível')
                                <span class="inline-block rounded-full bg-green-500/90 text-white border border-green-400/60 px-3 py-0.5 text-xs font-medium shadow">Disponível</span>
                            @elseif ($livro->status === 'Indisponível')
                                <span class="inline-block rounded-full bg-red-500/90 text-white border border-red-400/60 px-3 py-0.5 text-xs font-medium shadow">Indisponível</span>
                            @else
                                <span class="inline-block rounded-full bg-gray-500/90 text-white border border-gray-400/60 px-3 py-0.5 text-xs font-medium shadow">{{ $livro->status ?? 'N/A' }}</span>
                            @endif
                        </span>
                    </div>

                    <div class="p-4 border-b border-white/10">
                        <h2 class="text-lg font-bold leading-snug">{{ $livro->titulo }}</h2>
                        <p class="text-sm text-gray-300 mt-1">{{ $livro->autor }}</p>
                    </div>

                    <div class="p-4 flex-1 grid grid-cols-2 gap-x-3 gap-y-2 text-sm">
                        <span class="text-gray-400">Código</span>
                        <span class="text-right font-medium">{{ $livro->codigo }}</span>

                        <span class="text-gray-400">Ano</span>
                        <span class="text-right font-medium">{{ $livro->ano_publicacao }}</span>

                        <span class="text-gray-400">Estoque</span>
                        <span class="text-right font-medium">{{ $livro->estoque }}</span>

                        <span class="text-gray-400">Empréstimos ativos</span>
                        <span class="text-right font-medium">{{ $livro->emprestimos_ativos ?? '-' }}</span>
                    </div>

                    <div class="relative z-10 p-4 flex justify-end gap-2">
                        {{-- Botão Editar --}}
                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded cursor-pointer"
                            title="Editar livro" aria-label="Editar livro"
                            @click="$dispatch('abrir-edit-livro', {
                                livro: @js($livro->only(['id', 'titulo', 'autor', 'ano_publicacao', 'codigo', 'capa_url', 'estoque']))
                            })">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                            </svg>
                        </button>

                        {{-- Botão Deletar --}}
                        <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded cursor-pointer"
                            title="Deletar livro" aria-label="Deletar livro"
                            @click="$dispatch('abrir-del-livro', {
                                livro: @js($livro->only(['id', 'titulo']))
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
                    <p class="text-gray-500 dark:text-gray-400">Nenhum livro encontrado.</p>
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

@if (session('error'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed bottom-4 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3
                   rounded-lg border border-red-500 bg-red-500/10 backdrop-blur
                   px-5 py-3 shadow-lg text-sm text-red-600 dark:text-red-400"
            role="alert"
        >
            <span>{{ session('error') }}</span>
            <button type="button" @click="show = false" class="ml-2 opacity-70 hover:opacity-100" aria-label="Fechar">✕</button>
        </div>
@endif
@endsection