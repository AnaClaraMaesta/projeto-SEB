    @extends('turma.turmasPage')
    @section('show')

    <div class="bg-white text-black min-h-screen p-3 dark:bg-[#24272b] dark:text-white">

        
        <a href="{{ route('turmas') }}" class="m-3 inline-block text-sm text-gray-500 dark:text-gray-400 hover:underline">
            &larr; Voltar
        </a>
        
        <div class="bg-[#f0f0f0] dark:bg-[#3a3d41] p-10 rounded-lg shadow-md w-full max-w-xl mx-auto">
            <h1 class="text-center text-2xl font-bold">{{ $turma->nome_turma }}</h1>
        </div>
        
         <div class="m-3">
            <button type="button"
                    @click="$dispatch('abrir-add-aluno', { turma: {{ $turma->id }} })"
                    class="cursor-pointer text-white bg-[#3D2F2F] hover:bg-[#2F3D3D] px-4 py-2 rounded-md whitespace-nowrap">
                + Novo
            </button>

            <a href="{{ route('aluno.pdf', $turma->id) }}" 
                class="cursor-pointer text-white bg-[#3D2F2F] hover:bg-[#2F3D3D] px-4 py-2 rounded-md whitespace-nowrap">
                Emitir relatório
            </a>
        </div>
        
        <table class="w-full text-sm text-left">
            <thead class="bg-[#3D2F2F] text-white text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3">Estudante</th>
                    <th class="px-4 py-3">Livro</th>
                    <th class="px-4 py-3">Empréstimo</th>
                    <th class="px-4 py-3">Devolução</th>
                    <th class="px-4 py-3">Previsão</th>
                    <th class="px-4 py-3">Status</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-gray-200 dark:divide-gray-600" x-data='{ edit_aluno: false }'>
                @forelse($turma->alunos as $aluno)
                <tr class="bg-white dark:bg-[#3a3d41] hover:bg-[#e6eaf0] dark:hover:bg-[#52565b]">
                    <td class="px-4 py-3 font-medium">{{ $aluno->nome }}</td>
                    <td class="px-4 py-3">{{ $aluno->livro?->titulo ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $aluno->data_emprestimo?->format('d/m/Y') ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $aluno->data_devolucao?->format('d/m/Y') ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $aluno->data_devolucao_prevista?->format('d/m/Y') ?? '-' }}</td>
                     <td class="px-4 py-3 text-center">
                        @if ($aluno->status === 'No prazo')
                        <span class="inline-block rounded-full bg-green-500/15 text-green-700 dark:text-green-400 border border-green-500/40 px-3 py-0.5 text-xs font-medium">No prazo</span>
                        @elseif ($aluno->status === 'Atrasado')
                        <span class="inline-block rounded-full bg-red-500/15 text-red-700 dark:text-red-400 border border-red-500/40 px-3 py-0.5 text-xs font-medium">Atrasado</span>
                        @elseif( $aluno->status === 'Sem emprestimo')
                        <span class="inline-block rounded-full bg-gray-500/15 text-gray-600 dark:text-gray-300 border border-gray-500/30 px-3 py-0.5 text-xs font-medium">Sem empréstimo</span>
                        @endif
                    </td>

                    {{-- edit --}}

                    <td class="px-4 py-3">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2"
                         @click="$dispatch('abrir-edit-aluno', {
                            turma: {{ $turma->id }},
                            aluno: @js($aluno->only(['id', 'nome', 'telefone', 'endereco']))
                        })">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                            </svg>
                        </button>

                    </td>
                    <td>
                        {{-- delete --}}
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2"
                        @click="$dispatch('abrir-del-aluno', {
                            turma: {{ $turma->id }},
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
                    <td colspan="6">
                        <div class="flex flex-col items-center justify-center text-center py-16">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-16 mb-3 text-gray-400 dark:text-gray-500" viewBox="0 0 16 16">
                                <path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5H2zm13-3H1V2h14v1z"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">Nenhum aluno encontrado.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

</div>

@endsection