@extends('emprestimo.emprestimoPage')
@section('index')

    <div class="bg-[#e6eaf0] text-black min-h-screen p-3 dark:bg-[#24272b] dark:text-white">
        <div class="m-3 gap-2 flex justify-between">
            <div class="justify-start">
                <a href="{{ route('turma.index') }}" class="m-3 inline-block text-sm text-gray-500 dark:text-gray-400 hover:underline">
                    &larr; Voltar
                </a>
            </div>
        </div>

        <div class="m-3 flex gap-2">
            <button type="button"
                    @click="$dispatch('abrir-add-emprestimo')"
                    class="cursor-pointer text-white bg-[#3D2F2F] hover:bg-[#2F3D3D] px-4 py-2 rounded-md whitespace-nowrap">
                + Novo Empréstimo
            </button>
            
        </div>

        <div class="overflow-x-auto m-3 shadow-lg rounded-lg">
            <table class="w-full text-sm text-left">
                <thead class="bg-[#3D2F2F] text-white text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3">Estudante</th>
                        <th class="px-4 py-3">Turma</th>
                        <th class="px-4 py-3">Livro</th>
                        <th class="px-4 py-3">Empréstimo</th>
                        <th class="px-4 py-3">Previsão</th>
                        <th class="px-4 py-3">Devolução</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Ações</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($emprestimo as $registro)
                    <tr class="bg-white dark:bg-[#3a3d41] hover:bg-[#e6eaf0] dark:hover:bg-[#52565b]">
                        <td class="px-4 py-3 font-medium">{{ $registro->aluno?->nome ?? '-' }}</td>
                        <td class="px-4 py-3 font-medium">{{ $registro->aluno?->turma?->nome_turma ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $registro->livro?->titulo ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $registro->data_emprestimo?->format('d/m/Y') ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $registro->data_devolucao_prevista?->format('d/m/Y') ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $registro->data_devolucao?->format('d/m/Y') ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if ($registro->status === 'No prazo')
                                <span class="inline-block rounded-full bg-green-500/15 text-green-700 dark:text-green-400 border border-green-500/40 px-3 py-0.5 text-xs font-medium">No prazo</span>
                            @elseif ($registro->status === 'Atrasado')
                                <span class="inline-block rounded-full bg-red-500/15 text-red-700 dark:text-red-400 border border-red-500/40 px-3 py-0.5 text-xs font-medium">Atrasado</span>
                            @elseif ($registro->status === 'Devolvido')
                                <span class="inline-block rounded-full bg-gray-500/15 text-gray-600 dark:text-gray-300 border border-gray-500/30 px-3 py-0.5 text-xs font-medium">Devolvido</span>
                            @else
                                <span class="inline-block rounded-full bg-gray-500/15 text-gray-600 dark:text-gray-300 border border-gray-500/30 px-3 py-0.5 text-xs font-medium">{{ $registro->status ?? 'N/A' }}</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            @if (! $registro->data_devolucao)
                                <button type="button"
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1.5 px-3 rounded inline-flex items-center cursor-pointer"
                                    @click="$dispatch('abrir-devolver-emprestimo', {
                                        emprestimo: @js([
                                            'id' => $registro->id,
                                            'aluno' => $registro->aluno?->nome,
                                            'livro' => $registro->livro?->titulo,
                                        ])
                                    })">
                                    Devolver
                                </button>
                            @else
                                <span class="text-gray-500 dark:text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="flex flex-col items-center justify-center text-center py-16">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-16 mb-3 text-gray-400 dark:text-gray-500" viewBox="0 0 16 16">
                                        <path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5H2zm13-3H1V2h14v1z"/>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">Nenhum empréstimo encontrado.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
            x-init="setTimeout(() => show = false, 4000)"
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
