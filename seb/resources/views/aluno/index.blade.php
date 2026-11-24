@extends('aluno.alunosPage')
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
        
        <div class="m-3 flex gap-2">
            <button type="button"
                    @click="$dispatch('abrir-add-aluno')"
                    class="cursor-pointer text-white bg-[#3D2F2F] hover:bg-[#2F3D3D] px-4 py-2 rounded-md whitespace-nowrap">
                + Novo Aluno
            </button>

            <a href="{{ route('aluno.pdfGeral') }}" 
                class="cursor-pointer text-white bg-[#3D2F2F] hover:bg-[#2F3D3D] px-4 py-2 rounded-md whitespace-nowrap inline-flex items-center">
                Emitir relatório
            </a>
        </div>

        <div class="overflow-x-auto m-3 shadow-lg rounded-lg">
            <table class="w-full text-sm text-left">
                <thead class="bg-[#3D2F2F] text-white text-xs uppercase tracking-wide">
                  <tr>
                    <th class="px-4 py-3">Estudante</th>
                    <th class="px-4 py-3">Contato</th>
                    <th class="px-4 py-3">Turma</th>
                    <th class="px-4 py-3">Turno</th>
                    <th class="px-4 py-3">Livro</th>
                    <th class="px-4 py-3">Empréstimo</th>
                    <th class="px-4 py-3">Devolução</th>
                    <th class="px-4 py-3">Previsão</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-center">Ações</th>
                </tr>
                </thead>
                
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($alunos as $aluno)
                    <tr class="bg-white dark:bg-[#3a3d41] hover:bg-[#e6eaf0] dark:hover:bg-[#52565b]">
                    <td class="px-4 py-3 font-medium">{{ $aluno->nome }}</td>
                    <td class="px-4 py-3 font-medium">{{ $aluno->telefone }}</td>
                    <td class="px-4 py-3 font-medium">{{ $aluno->turma->nome_turma }}</td>
                    <td class="px-4 py-3 font-medium">{{ $aluno->turma->turno_turma }}</td>
                    <td class="px-4 py-3">{{ $aluno->emprestimoAtivo?->livro?->titulo ?? $aluno->livro?->titulo ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $aluno->emprestimoAtivo?->data_emprestimo?->format('d/m/Y') ?? $aluno->data_emprestimo?->format('d/m/Y') ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $aluno->data_devolucao?->format('d/m/Y') ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $aluno->emprestimoAtivo?->data_devolucao_prevista?->format('d/m/Y') ?? $aluno->data_devolucao_prevista?->format('d/m/Y') ?? '-' }}</td>
                     <td class="px-4 py-3 text-center">
                        @if ($aluno->status === 'No prazo')
                        <span class="inline-block rounded-full bg-green-500/15 text-green-700 dark:text-green-400 border border-green-500/40 px-3 py-0.5 text-xs font-medium">{{ $aluno->statusLabel }}</span>
                        @elseif ($aluno->status === 'Atrasado')
                        <span class="inline-block rounded-full bg-red-500/15 text-red-700 dark:text-red-400 border border-red-500/40 px-3 py-0.5 text-xs font-medium">{{ $aluno->statusLabel }}</span>
                        @elseif( $aluno->status === 'Sem emprestimo')
                        <span class="inline-block rounded-full bg-gray-500/15 text-gray-600 dark:text-gray-300 border border-gray-500/30 px-3 py-0.5 text-xs font-medium">{{ $aluno->statusLabel }}</span>
                        @endif
                    </td>

                        <!-- Coluna de Ações -->
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            {{-- Botão Empréstimo (somente sem empréstimo ativo) --}}
                            @if (! $aluno->emprestimoAtivo)
                                <button type="button"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded inline-flex items-center cursor-pointer"
                                    title="Adicionar empréstimo"
                                    aria-label="Adicionar empréstimo"
                                    @click="$dispatch('abrir-add-emprestimo', { aluno_id: {{ $aluno->id }} })">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16">
                                        <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                                    </svg>
                                </button>
                            @endif

                            {{-- Botão Editar --}}
                            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1.5 px-3 rounded inline-flex items-center cursor-pointer"
                                @click="$dispatch('abrir-edit-aluno', {
                                    turma: {{ $aluno->turma_id }},
                                    aluno: @js($aluno->only(['id', 'nome', 'telefone', 'status']))
                                })">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                </svg>
                            </button>

                            {{-- Botão Deletar --}}
                            <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1.5 px-3 rounded inline-flex items-center ml-1 cursor-pointer"
                                @click="$dispatch('abrir-del-aluno', {
                                    turma: {{ $aluno->turma_id }},
                                    aluno: @js($aluno->only(['id', 'nome']))
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
                            <td colspan="10">
                                <div class="flex flex-col items-center justify-center text-center py-16">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-16 mb-3 text-gray-400 dark:text-gray-500" viewBox="0 0 16 16">
                                        <path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5H2zm13-3H1V2h14v1z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">Nenhum Aluno encontrado.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

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