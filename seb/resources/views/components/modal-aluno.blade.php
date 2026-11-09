@props(['turmas'])

<div x-data = "{
    modal:null,
    turmaId: '',
    aluno: {},
    baseUrl: '{{ url('/turmas') }}',

    abrir(tipoModal, dados = {}){
        this.modal = tipoModal;
        this.turmaId = dados.turma ?? this.turmaId;
        this.aluno = dados.aluno ?? {}; 
    },
    fechar() {this.modal = null;},

    get actionAdd()  { return `${this.baseUrl}/${this.turmaId}/aluno`; },
    get actionEdit() { return `${this.baseUrl}/${this.turmaId}/aluno/${this.aluno.id}`; },
    get actionDel()  { return `${this.baseUrl}/${this.turmaId}/aluno/${this.aluno.id}`; },
}"
    @abrir-add-aluno.window="abrir('add', $event.detail ?? {})"
    @abrir-edit-aluno.window="abrir('edit', $event.detail ?? {})"
    @abrir-del-aluno.window="abrir('del', $event.detail ?? {})"
    @keydown.escape.window="fechar()">
    
    <div x-show="modal" x-cloak x-transition.opacity @click.self="fechar()" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm" x-data = "{ loading: false}">
        
        {{-- adicionar --}}

        <div x-show="modal === 'add'" class="w-[64vw] h-[90vh] flex flex-col rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Novo aluno</h2>
                
            <form :action="actionAdd" method="POST" class="flex flex-col flex-1 min-h-0" @submit="loading = true; axios.post(actionAdd, new FormData($event.target)).then(()=> {loading = false})">
                @csrf

                <div class="flex flex-col gap-1">
                    <label class="font-bold">Turma:</label>
                    <select x-model="turmaId" required
                    class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                        <option value="" disabled class="text-black">Selecione...</option>
                        @foreach ($turmas as $t)
                            <option value="{{ $t->id }}" class="text-black">{{ $t->nome_turma }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1 p-1.5">
                   <label for="nome" class="font-bold">Nome:</label>
                    <input type="text" name="nome" id="nome"
                    class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                </div>

                <div class="flex flex-col gap-1 p-1.5">
                    <label for="telefone" class="font-bold">Contato do responsável:</label>
                    <input type="text" name="telefone" id="telefone"
                    class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                </div>

                <div class="flex flex-col gap-1 p-1.5">
                    <label for="status" class="font-bold">Status:</label>
                    <select name="status" id="status" class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                        <option value="Sem emprestimo" class="text-black">Sem empréstimo</option>
                        <option value="No prazo" class="text-black">No prazo</option>
                        <option value="Atrasado" class="text-black">Atrasado</option>
                    </select>
                </div>


                <div class="flex justify-end p-1.5" class="w-full max-w-lg rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
                        
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    :disabled="loading"
                    :class="{ 'opacity-50 cursor-not-allowed': loading }"
                    >
                        <span  x-text="loading ? 'Salvando...' : 'Criar Aluno'"></span>

                    </button>
                </div>

            </form>
                
        </div>

        {{-- editar --}}

        <div x-show="modal === 'edit'" class="w-[64vw] h-[90vh] flex flex-col rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Editar aluno</h2>
            <form :action="actionEdit" method="POST">
                @csrf
                @method('PUT')

                <div class="flex flex-col gap-1 p-1.5">
                    <label for="nome" class="font-bold">Nome:</label>
                    <p class='text-red-500'>*</p>
                        <input type="text" name="nome" id="nome"
                        class="rounded-md bg-white/10 border border-white/20 px-2 py-1"
                        x-model="aluno.nome"
                >
                </div>

                <div class="flex flex-col gap-1 p-1.5">
                    <label for="telefone" class="font-bold">Contato do responsável:</label>
                    <p class='text-red-500'>*</p>
                    <input type="text" name="telefone" id="telefone"
                    class="rounded-md bg-white/10 border border-white/20 px-2 py-1"
                    x-model="aluno.telefone"
                >
                </div>

                <div class="flex flex-col gap-1 p-1.5">
                    <label for="status" class="font-bold">Status:</label>
                    <select name="status" id="status" class="rounded-md bg-white/10 border border-white/20 px-2 py-1"
                    x-model="aluno.status">
                        <option value="Sem emprestimo" class="text-black">Sem empréstimo</option>
                        <option value="No prazo" class="text-black">No prazo</option>
                        <option value="Atrasado" class="text-black">Atrasado</option>
                    </select>
                </div>


                <div class="flex justify-end p-1.5">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    :disabled="loading"
                    :class="{ 'opacity-50 cursor-not-allowed': loading }"
                    >
                        <span  x-text="loading ? 'Salvando...' : 'Salvar'"></span>
                    </button>
                </div>

            </form>
        </div>

        {{-- deletar --}}

        <div x-show="modal === 'del'" class="w-full max-w-md rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Deletar aluno</h2>
            <form :action="actionDel" method="POST" class="flex flex-col gap-4">
                @csrf
                @method('DELETE')
                <p>Tem certeza que deseja deletar o aluno <span class="font-bold" x-text="aluno.nome"></span>?</p>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="fechar()" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20">Cancelar</button>
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Deletar
                    </button>
                </div>
            </form>
        </div>

</div>