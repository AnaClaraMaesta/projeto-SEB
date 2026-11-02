@extends('aluno.alunosPage')
@section('index')
    <div class="bg-[#e6eaf0] text-black min-h-screen p-3 dark:bg-[#24272b] dark:text-white">
        <div class="m-3 gap-2 flex justify-between">
            
            <div class="justify-start">
                <a href="{{ route('turmas') }}" class="m-3 inline-block text-sm text-gray-500 dark:text-gray-400 hover:underline">
                    &larr; Voltar
                </a>

                <a 
                    href="{{ route('aluno.pdfGeral') }}"
                    class="btn cursor-pointer text-white bg-[#3D2F2F] hover:bg-[#2F3D3D] px-4 py-2 rounded-md whitespace-nowrap">
                    Emitir relatório
                </a>
            </div>
        </div>




        <div class="m-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse($alunos as $aluno)
            
            <div class="relative rounded-lg overflow-hidden shadow-lg bg-[#3D2F2F] dark:bg-[#464a4f] text-white flex flex-col transition-transform duration-300 ease-out hover:scale-105">
                
                <div class="p-4 border-b border-white/10">
                    <h2 class="text-lg font-bold">{{ $aluno->nome }}</h2>
                </div>

                 <div class="p-4 flex-1 transition-transform duration-300">
                    <p class="text-gray-300">Telefone: {{ $aluno->telefone }}</p>
                    <p class="text-gray-300">Turma: {{ $aluno->turma->nome_turma }}</p>
                    <p class="text-gray-300">Status: {{ $aluno->status }}</p>
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

@endsection