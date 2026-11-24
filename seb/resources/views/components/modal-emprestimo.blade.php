@props(['alunos' => [], 'livros' => []])

<div x-data="{
    modal: null,
    emprestimo: {},
    totalDevolvidos: 0,
    baseUrl: '{{ url('/emprestimo') }}',

    abrir(tipoModal, dados = {}) {
        this.modal = tipoModal;
        this.emprestimo = dados.emprestimo ?? {};
        this.totalDevolvidos = dados.total ?? 0;

        if (tipoModal === 'add') {
            this.formAdd = {
                aluno_id: dados.aluno_id ? String(dados.aluno_id) : '',
                livro_id: dados.livro_id ? String(dados.livro_id) : '',
                dias_prazo: '',
            };
        }
    },

    formAdd: {
        aluno_id: '',
        livro_id: '',
        dias_prazo: '',
    },

    fechar() { 
        this.modal = null; 
    },

    get actionAdd()              { return `${this.baseUrl}`; },
    get actionDevolver()         { return `${this.baseUrl}/${this.emprestimo.id}/devolver`; },
    get actionExcluir()          { return `${this.baseUrl}/${this.emprestimo.id}`; },
    get actionExcluirHistorico() { return `${this.baseUrl}/devolvidos`; }
}"
    @abrir-add-emprestimo.window="abrir('add', $event.detail ?? {})"
    @abrir-devolver-emprestimo.window="abrir('devolver', $event.detail ?? {})"
    @abrir-excluir-emprestimo.window="abrir('excluir', $event.detail ?? {})"
    @abrir-excluir-historico.window="abrir('excluir-historico', $event.detail ?? {})"
    @keydown.escape.window="fechar()"
>

    <!-- Fundo Escuro / Backdrop -->
    <div x-show="modal" x-cloak x-transition.opacity @click.self="fechar()" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        
        {{-- 1. NOVO EMPRÉSTIMO --}}
        <div x-show="modal === 'add'" class="w-[64vw] max-h-[90vh] overflow-y-auto flex flex-col rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Novo Empréstimo</h2>
                
            <form :action="actionAdd" method="POST" class="flex flex-col gap-3 flex-1 min-h-0">
                @csrf

                <div class="flex flex-col gap-1">
                    <label for="aluno_id" class="font-bold">Aluno:</label>
                    <select name="aluno_id" id="aluno_id" x-model="formAdd.aluno_id" required
                        class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                        <option value="" disabled class="text-black">Selecione...</option>
                        @foreach ($alunos as $aluno)
                            <option value="{{ $aluno->id }}" class="text-black">
                                {{ $aluno->nome }} — {{ $aluno->turma?->nome_turma ?? 'Sem turma' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label for="livro_id" class="font-bold">Livro:</label>
                    <select name="livro_id" id="livro_id" x-model="formAdd.livro_id" required
                        class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                        <option value="" disabled class="text-black">Selecione...</option>
                        @foreach ($livros as $livro)
                            <option value="{{ $livro->id }}" class="text-black">
                                {{ $livro->titulo }} (cód. {{ $livro->codigo }}) — {{ $livro->estoque }} em estoque
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label for="dias_prazo" class="font-bold">Prazo (dias) Max:30</label>
                    <input type="number" name="dias_prazo" id="dias_prazo" x-model="formAdd.dias_prazo"
                        min="1" max="30" required
                        class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="fechar()" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20">Cancelar</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Registrar Empréstimo
                    </button>
                </div>
            </form>
        </div>

        {{-- 2. REGISTRAR DEVOLUÇÃO --}}
        <div x-show="modal === 'devolver'" class="w-full max-w-md rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Registrar Devolução</h2>

            <form :action="actionDevolver" method="POST" class="flex flex-col gap-4">
                @csrf
                @method('PUT')

                <p>
                    Confirmar a devolução do livro 
                    <span class="font-bold" x-text="emprestimo.livro"></span> 
                    por <span class="font-bold" x-text="emprestimo.aluno"></span>?
                </p>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="fechar()" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20">Cancelar</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Devolver
                    </button>
                </div>
            </form>
        </div>

        {{-- 3. EXCLUIR REGISTRO DE DEVOLUÇÃO --}}
        <div x-show="modal === 'excluir'" class="w-full max-w-md rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Excluir Registro de Devolução</h2>

            <form :action="actionExcluir" method="POST" class="flex flex-col gap-4">
                @csrf
                @method('DELETE')

                <p>
                    Excluir o registro de devolução do livro
                    <span class="font-bold" x-text="emprestimo.livro"></span>
                    por <span class="font-bold" x-text="emprestimo.aluno"></span>?
                </p>

                <p class="text-sm text-gray-300">O estoque do livro não será alterado.</p>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="fechar()" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20">Cancelar</button>
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Excluir
                    </button>
                </div>
            </form>
        </div>

        {{-- 4. EXCLUIR HISTÓRICO DE DEVOLUÇÕES --}}
        <div x-show="modal === 'excluir-historico'" class="w-full max-w-md rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Excluir Histórico de Devoluções</h2>

            <form :action="actionExcluirHistorico" method="POST" class="flex flex-col gap-4">
                @csrf
                @method('DELETE')

                <p>Excluir <span class="font-bold" x-text="totalDevolvidos"></span> registro(s) de devolução?</p>

                <p class="text-sm text-gray-300">Esta ação remove os registros antigos da lista. O estoque não será alterado.</p>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="fechar()" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20">Cancelar</button>
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Excluir tudo
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>