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

        <div class="m-3 flex flex-wrap gap-2 justify-between">
            <div class="justify-start">

                <button type="button"
                @click="$dispatch('abrir-add-emprestimo')"
                class="cursor-pointer text-white bg-[#3D2F2F] hover:bg-[#2F3D3D] px-4 py-2 rounded-md whitespace-nowrap">
                + Novo Empréstimo
            </button>

            @if ($devolvidos > 0)
                <button type="button"
                        @click="$dispatch('abrir-excluir-historico', { total: {{ $devolvidos }} })"
                        class="cursor-pointer text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded-md whitespace-nowrap">
                    Excluir histórico ({{ $devolvidos }})
                </button>
            @endif
        </div>


            <div class="justify-end w-full sm:w-auto">

                <form id="form-periodo-emprestimo" class="w-full sm:w-80" method="GET" action="{{ route('emprestimo.index') }}">
                    <div class="flex items-center rounded-md bg-[#3D2F2F] text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-3 shrink-0 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>

                        <input type="text" id="periodo-emprestimo" readonly
                            placeholder="Selecione o período"
                            class="w-full bg-transparent border-0 px-3 py-2.5 text-sm text-white placeholder:text-white/60 focus:ring-0 focus:outline-none cursor-pointer">

                        <input type="hidden" name="data_inicio" id="periodo-data-inicio" value="{{ $dataInicio }}">
                        <input type="hidden" name="data_fim" id="periodo-data-fim" value="{{ $dataFim }}">

                        @if ($dataInicio || $dataFim)
                            <a href="{{ route('emprestimo.index') }}"
                                class="mr-2 shrink-0 rounded p-1 text-white/70 hover:bg-white/10 hover:text-white"
                                title="Limpar período" aria-label="Limpar período">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if ($errors->any())
            <div class="m-3 rounded-md border border-red-500 bg-red-500/10 px-4 py-3 text-sm text-red-700 dark:text-red-400">
                {{ $errors->first() }}
            </div>
        @endif

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
                            @elseif ($registro->status === 'Em atraso')
                                <span class="inline-block rounded-full bg-red-500/15 text-red-700 dark:text-red-400 border border-red-500/40 px-3 py-0.5 text-xs font-medium">Em atraso</span>
                            @elseif ($registro->status === 'Devolvido')
                                <span class="inline-block rounded-full bg-gray-500/15 text-gray-600 dark:text-gray-300 border border-gray-500/30 px-3 py-0.5 text-xs font-medium">Devolvido</span>
                            @else
                                <span class="inline-block rounded-full bg-gray-500/15 text-gray-600 dark:text-gray-300 border border-gray-500/30 px-3 py-0.5 text-xs font-medium">{{ $registro->status ?? 'N/A' }}</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            {{-- Reimpressão da nota do empréstimo --}}
                            <a href="{{ route('emprestimo.nota', $registro) }}"
                                target="_blank" rel="noopener"
                                title="Reimprimir nota do empréstimo"
                                aria-label="Reimprimir nota do empréstimo"
                                class="bg-[#3D2F2F] hover:bg-[#2F3D3D] text-white font-bold py-1.5 px-3 rounded inline-flex items-center cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                                    <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                                    <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
                                </svg>
                                <span class="ml-1">Nota</span>
                            </a>

                            @if (! $registro->data_devolucao)
                                <button type="button"
                                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1.5 px-3 rounded inline-flex items-center ml-1 cursor-pointer"
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
                                <button type="button"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1.5 px-3 rounded inline-flex items-center cursor-pointer"
                                    @click="$dispatch('abrir-excluir-emprestimo', {
                                        emprestimo: @js([
                                            'id' => $registro->id,
                                            'aluno' => $registro->aluno?->nome,
                                            'livro' => $registro->livro?->titulo,
                                        ])
                                    })">
                                    Excluir
                                </button>
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

        <div class="m-3">
            {{ $emprestimo->links() }}
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

@if (session('nota_emprestimo_id'))
    {{-- Gera e baixa automaticamente a nota do empréstimo recém-cadastrado. --}}
    <a id="nota-emprestimo-gerada"
        href="{{ route('emprestimo.nota', ['emprestimo' => session('nota_emprestimo_id'), 'download' => 1]) }}"
        download="nota_emprestimo.pdf"
        style="display: none;"
        aria-hidden="true"></a>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nota = document.getElementById('nota-emprestimo-gerada');

            if (nota) {
                nota.click();
            }
        });
    </script>
@endif

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .flatpickr-calendar {
            border-radius: 8px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, .18);
        }

        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange,
        .flatpickr-day.selected.inRange,
        .flatpickr-day.startRange.inRange,
        .flatpickr-day.endRange.inRange {
            background: #3D2F2F;
            border-color: #3D2F2F;
        }

        .flatpickr-day.inRange {
            background: #e6eaf0;
            border-color: #e6eaf0;
            box-shadow: -5px 0 0 #e6eaf0, 5px 0 0 #e6eaf0;
        }

        .flatpickr-day.today {
            border-color: #3D2F2F;
        }

        .flatpickr-day.today:hover {
            background: #3D2F2F;
            border-color: #3D2F2F;
            color: #fff;
        }

        @media (prefers-color-scheme: dark) {
            .flatpickr-calendar {
                background: #3a3d41;
                box-shadow: 0 12px 24px rgba(0, 0, 0, .45);
            }

            .flatpickr-months .flatpickr-month,
            .flatpickr-current-month .flatpickr-monthDropdown-months,
            .flatpickr-current-month input.cur-year,
            .flatpickr-weekdays,
            span.flatpickr-weekday,
            .flatpickr-day {
                color: #e5e7eb;
                fill: #e5e7eb;
            }

            .flatpickr-day:hover {
                background: #52565b;
                border-color: #52565b;
            }

            .flatpickr-day.prevMonthDay,
            .flatpickr-day.nextMonthDay {
                color: #8b9096;
            }

            .flatpickr-day.inRange {
                background: #52565b;
                border-color: #52565b;
                box-shadow: -5px 0 0 #52565b, 5px 0 0 #52565b;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const campo = document.getElementById('periodo-emprestimo');

            if (! campo || typeof flatpickr === 'undefined') {
                return;
            }

            const formulario = document.getElementById('form-periodo-emprestimo');
            const inicio = document.getElementById('periodo-data-inicio');
            const fim = document.getElementById('periodo-data-fim');

            const paraISO = (data) => {
                const mes = String(data.getMonth() + 1).padStart(2, '0');
                const dia = String(data.getDate()).padStart(2, '0');

                return `${data.getFullYear()}-${mes}-${dia}`;
            };

            const datasIniciais = [inicio.value, fim.value]
                .filter(Boolean)
                .map((valor) => new Date(`${valor}T00:00:00`));

            flatpickr(campo, {
                mode: 'range',
                dateFormat: 'd/m/Y',
                rangeSeparator: ' até ',
                locale: 'pt',
                disableMobile: true,
                defaultDate: datasIniciais.length ? datasIniciais : null,
                onChange: function (datas) {
                    if (datas.length !== 2) {
                        return;
                    }

                    inicio.value = paraISO(datas[0]);
                    fim.value = paraISO(datas[1]);
                    formulario.submit();
                },
            });
        });
    </script>
@endpush
@endsection
