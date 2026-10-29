    @extends('turma.turmasPage')
    @section('show')

    <div class="bg-white text-black min-h-screen p-3 dark:bg-[#24272b] dark:text-white">

        
        <a href="{{ route('turmas') }}" class="m-3 inline-block text-sm text-gray-500 dark:text-gray-400 hover:underline">
            &larr; Voltar
        </a>
        
        <div class="bg-[#f0f0f0] dark:bg-[#3a3d41] p-10 rounded-lg shadow-md w-full max-w-xl mx-auto">
            <h1 class="text-center text-2xl font-bold">{{ $turma->nome_turma }}</h1>
        </div>
        
        
         <div x-data="{ add_aluno: false }"
            @abrir-add-aluno.window="add_aluno = true"
            @keydown.escape.window="add_aluno = false"
            class="m-3">

            <button type="button" @click="$dispatch('abrir-add-aluno')"
                    class="cursor-pointer text-white bg-[#3D2F2F] hover:bg-[#2F3D3D] px-4 py-2 rounded-md whitespace-nowrap">
                + Novo
            </button>

        
            <div x-show="add_aluno" x-cloak x-transition.opacity
             @click.self="add_aluno = false"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

        
                <div class="w-[64vw] h-[90vh] flex flex-col rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            
                    <form action="{{route('aluno.store')}}" method="post" class="flex flex-col flex-1 min-h-0">
                        @csrf

                        <div class="flex flex-col gap-1 p-1.5">
                            <label for="nome_aluno" class="font-bold">Nome:</label>
                            <input type="text" name="nome_aluno" id="nome_aluno"
                                class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                        </div>

                        <div class="flex flex-col gap-1 p-1.5">
                            <label for="telefone_aluno" class="font-bold">telefone:</label>
                            <input type="text" name="telefone_aluno" id="telefone_aluno"
                                class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                        </div>

                        <div class="flex flex-col gap-1 p-1.5">
                            <label for="endereco_aluno" class="font-bold">endereco:</label>
                            <input type="text" name="endereco_aluno" id="endereco_aluno"
                                class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                        </div>

                        

                        <div class="flex justify-end p-1.5">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Criar Aluno
                            </button>
                        </div>

                    </form>
                        
                </div>
        
            </div>

        </div>
        
        <table class="w-full mt-10 border-collapse border border-gray-300 dark:border-gray-600">
            <thead>
                <tr class="bg-[#e6eaf0] dark:bg-[#464a4f]">
                    <th>Estudantes</th>
                    <th>Livro</th>
                    <th>Emprestimo</th>
                    <th>Devolução</th>
                    <th>Previsão</th>
                    <th>Status</th> 
                    <th>Ações</th>
                </tr>
            </thead>    
            <tbody>
                @forelse($turma->alunos as $aluno)
                    <tr class="bg-[#e6eaf0] dark:bg-[#464a4f]">
                        <td>{{ $aluno->nome }}</td>
                        <td>{{ $aluno->livro?->titulo ?? '-' }}</td>
                        <td>{{ $aluno->data_emprestimo->format('d/m/Y') ?? '-' }}</td>
                        <td>{{ $aluno->data_devolucao->format('d/m/Y') ?? '-' }}</td>
                        <td>{{ $aluno->data_devolucao_prevista ? \Carbon\Carbon::parse($aluno->data_devolucao_prevista)->format('d/m/Y') : 'N/A' }}</td>
                        <td>{{ $aluno->status ?? '-' }}</td>
                        <td>

                            <i class="bi bi-pencil"></i>

                            {{-- <form action="{{ route('aluno.destroy', ['aluno' => $aluno->id]) }}" method="POST" class="inline" onSubmit="showAlert(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">Deletar</button>
                            </form> --}}
                        </td>
                        <td>
                            {{-- <from action="{{ route('aluno.edit', ['aluno' => $aluno->id]) }}" method="GET" class="inline">
                                @csrf
                                <button type="submit" class="text-blue-500 hover:text-blue-700">Editar</button>
                            </from> --}}
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