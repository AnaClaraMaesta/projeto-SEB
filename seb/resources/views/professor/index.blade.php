@extends('professor.professoresPage')
@section('index')

    <div class="bg-[#e6eaf0] text-black min-h-screen p-3 dark:bg-[#24272b] dark:text-white">
        <div class="m-3 gap-2 flex justify-between">
            <div class="justify-start">
                <a href="{{ route('turma.index') }}" class="m-3 inline-block text-sm text-gray-500 dark:text-gray-400 hover:underline">
                    &larr; Voltar
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="m-3 rounded-md border border-red-500 bg-red-500/10 px-4 py-3 text-sm text-red-700 dark:text-red-400">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="m-3 flex flex-wrap gap-2 justify-between">
            <div class="flex gap-2">
                <button type="button"
                        @click="$dispatch('abrir-add-professor')"
                        class="cursor-pointer text-white bg-[#3D2F2F] hover:bg-[#2F3D3D] px-4 py-2 rounded-md whitespace-nowrap">
                    + Novo Professor
                </button>
            </div>

            <form class="max-w-2xl w-full sm:w-auto" method="GET" action="{{ route('professor.index') }}">
                <div class="relative flex rounded-md bg-[#3D2F2F] text-white">
                    <input type="search" value="{{ $search }}" name="search"
                        class="px-3 py-2.5 bg-neutral-secondary-medium border text-heading text-sm focus:ring-brand focus:border-brand block w-full placeholder:text-body"
                        placeholder="Buscar por nome, e-mail, telefone ou turma">
                    <button type="submit" class="inline-flex items-center box-border border text-white bg-[#3D2F2F] cursor-pointer hover:bg-[#2F3D3D] border-transparent focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-e-base text-sm px-4 py-2.5 focus:outline-none">
                        <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/></svg>
                        Buscar
                    </button>

                    @if ($search)
                        <a href="{{ route('professor.index') }}"
                            class="inline-flex items-center text-white bg-[#3D2F2F] hover:bg-[#2F3D3D] text-sm px-4 py-2.5 focus:outline-none">
                            Limpar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto m-3 shadow-lg rounded-lg">
            <table class="w-full text-sm text-left">
                <thead class="bg-[#3D2F2F] text-white text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3">Nome</th>
                        <th class="px-4 py-3">E-mail</th>
                        <th class="px-4 py-3">Telefone</th>
                        <th class="px-4 py-3">Turmas</th>
                        <th class="px-4 py-3 text-center">Ações</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($professores as $professor)
                    <tr class="bg-white dark:bg-[#3a3d41] hover:bg-[#e6eaf0] dark:hover:bg-[#52565b]">
                        <td class="px-4 py-3 font-medium">{{ $professor->nome }}</td>
                        <td class="px-4 py-3 font-medium">{{ $professor->email }}</td>
                        <td class="px-4 py-3 font-medium">{{ $professor->telefone }}</td>
                        <td class="px-4 py-3 font-medium">{{ $professor->turmas->pluck('nome_turma')->join(', ') ?: '-' }}</td>

                        <!-- Coluna de Ações -->
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            {{-- Botão Editar --}}
                            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1.5 px-3 rounded inline-flex items-center cursor-pointer"
                                @click="$dispatch('abrir-edit-professor', {
                                    professor: @js($professor->only(['id', 'nome', 'email', 'telefone']))
                                })">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                </svg>
                            </button>

                            {{-- Botão Deletar --}}
                            <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1.5 px-3 rounded inline-flex items-center ml-1 cursor-pointer"
                                @click="$dispatch('abrir-del-professor', {
                                    professor: @js($professor->only(['id', 'nome']))
                                })">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="flex flex-col items-center justify-center text-center py-16">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-16 mb-3 text-gray-400 dark:text-gray-500" viewBox="0 0 16 16">
                                        <path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5H2zm13-3H1V2h14v1z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">Nenhum professor encontrado.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="m-3">
            {{ $professores->links() }}
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
