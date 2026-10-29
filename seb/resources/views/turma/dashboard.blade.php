@extends('turma.turmasPage')
@section('dashboard')

<script>
         function showAlert(event){
            event.preventDefault();
            const form = event.target.closest('form');
            Swal.fire({
                title: "Voce tem certeza?",
                theme: "auto",
                text: "Essa ação não pode ser desfeita!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sim, deletar!"
            }).then((result) => {
                if (result.isConfirmed) form.submit();
        });
    }
</script>

<script>
    function workedAlert(event){
        event.preventDefault();
        const from = event.target.closest('form');
        Swal.fire({
            title: "Drag me!",
            icon: "success",
            draggable: true
        }).then((result) => {
            if(result.isConfirmed) form.submit();
        });
    }
</script>
                

<div class="bg-[#e6eaf0] text-black min-h-screen p-3 dark:bg-[#24272b] dark:text-white">
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

                <form action="{{ route('turma.store') }}" method="POST" class="flex flex-col flex-1 min-h-0" onSubmit='workedAlert(event)'>
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
                            Criar Turma
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
        @forelse ($turmas as $turma)
        <div class="relative rounded-lg overflow-hidden shadow-lg bg-[#3D2F2F] dark:bg-[#464a4f] text-white flex flex-col transition-transform duration-300 ease-out hover:scale-105">
                
                <a href="{{ route('turma.show', $turma->id) }}" class="absolute inset-0 z-0  "
                        aria-label="Abrir turma {{ $turma->nome_turma }}">
                </a>

                <div class="p-4 border-b border-white/10">
                    <h2 class="text-lg font-bold">{{ $turma->nome_turma }}</h2>
                </div>
        
                <div class="p-4 flex-1 transition-transform duration-300">
                        
                    <p class="text-gray-300">{{ $turma->turno_turma }}</p>
                    <p class="text-gray-300">{{ $turma->ano_turma }}</p>
                    <p class="text-gray-300">{{ $turma->materia_turma }}</p>
                </div>

                
                {{-- rodape --}}
    
                <div class="relative z-10 p-4 flex justify-end" x-data="{ edit_turma: false }" @keydown.escape.window="edit_turma = false">
                    
                    {{-- update --}}

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2"
                        @click="edit_turma = !edit_turma"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                        </svg>
                    </button>

                    <template x-teleport="body">
                        
                         
                        <div 
                            x-show="edit_turma" 
                            x-transition.opacity
                            @click.self="edit_turma = false"
                            x-cloak
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
                        >
                            <div class="w-[64vw] h-[90vh] flex flex-col rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
    
                                <form action="{{ route('turma.update', $turma->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                
                                    <div class="flex flex-col gap-1 p-1.5">
                                        <label for="nome_turma" class="font-bold">Nome da turma:</label>
                                        <input type="text" name="nome_turma" id="nome_turma" value="{{ old('nome_turma', $turma->nome_turma) }}" required maxlength="255"
                                        class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                                    </div>
        
                                    <div class="flex flex-col gap-1 p-1.5">
                                        <label for="turno_turma" class="font-bold">Turno:</label>
                                    
                                        <select name="turno_turma" id="turno_turma" class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                                            <option value="" class="text-black">Selecione o turno</option>
                                            <option value="manha" class="text-black" @selected(old('turno_turma', $turma->turno_turma) === 'manha')>Manhã</option>
                                            <option value="tarde" class="text-black" @selected(old('turno_turma', $turma->turno_turma) === 'tarde')>Tarde</option>
                                            <option value="noite" class="text-black" @selected(old('turno_turma', $turma->turno_turma) === 'noite')>Noite</option>
                                            <option value="integral" class="text-black" @selected(old('turno_turma', $turma->turno_turma) === 'integral')>Integral</option>
                                        </select>
                                    </div>
                                                                
                                    <div class="flex flex-col gap-1 p-1.5">
                                        <label for="ano_turma" class="font-bold">Nível:</label>
                                            
                                        <select name="ano_turma" id="ano_turma" class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                                            <option value="" class="text-black">Selecione o nível</option>
                                            <option value="fundamentali" class="text-black" @selected(old('ano_turma', $turma->ano_turma) === 'fundamentali')>Fundamental I</option>
                                            <option value="fundamentalii" class="text-black" @selected(old('ano_turma', $turma->ano_turma) === 'fundamentalii')>Fundamental II</option>
                                            <option value="em" class="text-black" @selected(old('ano_turma', $turma->ano_turma) === 'em')>Ensino Médio</option>
                                        </select>
                                    </div>
                            
                                    <div class="flex flex-col gap-1 p-1.5">
                                        <label for="materia_turma" class="font-bold">Matéria:</label>
                                        <input type="text" name="materia_turma" id="materia_turma" class="rounded-md bg-white/10 border border-white/20 px-2 py-1" value="{{ old('materia_turma', $turma->materia_turma) }}" required>
                                    </div>
                            
                                    <div class="flex justify-end p-1.5">
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                           Salvar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </template>

                        {{-- deletar --}}
                                         
                    <form action="{{ route('turma.destroy', ['turma' => $turma->id]) }}" method="POST" class="inline" onSubmit="showAlert(event)">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                            </svg>
                        </button>
                    </form>

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

@endsection