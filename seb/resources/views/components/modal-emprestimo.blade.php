@props(['alunos' => [], 'livros' => []])

@php
    // Alunos com empréstimo ativo já vencido (usados na confirmação de cautela).
    $alunosEmAtraso = collect($alunos)
        ->filter(function ($aluno) {
            $ativos = $aluno->relationLoaded('emprestimo')
                ? $aluno->emprestimo->whereNull('data_devolucao')
                : $aluno->emprestimo()->whereNull('data_devolucao')->get();

            return $ativos->contains(fn ($emprestimo) => $emprestimo->data_devolucao_prevista?->lt(today()));
        })
        ->pluck('id')
        ->values();
@endphp

<div x-data="{
    modal: null,
    emprestimo: {},
    totalDevolvidos: 0,
    enviando: false,
    confirmarAtraso: false,
    alunosEmAtraso: @js($alunosEmAtraso),
    nomesAlunos: @js(collect($alunos)->pluck('nome', 'id')),
    baseUrl: '{{ url('/emprestimo') }}',

    abrir(tipoModal, dados = {}) {
        this.modal = tipoModal;
        this.enviando = false;
        this.confirmarAtraso = false;
        this.emprestimo = dados.emprestimo ?? {};
        this.totalDevolvidos = dados.total ?? 0;

        if (tipoModal === 'add') {
            this.formAdd = {
                aluno_id: dados.aluno_id ? String(dados.aluno_id) : '',
                livro_id: dados.livro_id ? String(dados.livro_id) : '',
                dias_prazo: '',
            };

            if (this.$refs.confirmadoInput) {
                this.$refs.confirmadoInput.value = '0';
            }
        }
    },

    formAdd: {
        aluno_id: '',
        livro_id: '',
        dias_prazo: '',
    },

    fechar() { 
        this.modal = null;
        this.enviando = false;
        this.confirmarAtraso = false;
    },

    get alunoSelecionadoEmAtraso() {
        return this.alunosEmAtraso.map(String).includes(String(this.formAdd.aluno_id));
    },

    get nomeAlunoSelecionado() {
        return this.nomesAlunos[this.formAdd.aluno_id] ?? '';
    },

    enviarEmprestimo(evento) {
        // Aluno em atraso: pede confirmação antes de enviar.
        if (this.alunoSelecionadoEmAtraso) {
            this.confirmarAtraso = true;

            return;
        }

        this.enviando = true;
        evento.target.submit();
    },

    confirmarEmprestimo() {
        // Atualiza o campo direto no DOM: o Alpine aplica bindings de forma
        // assíncrona e o submit logo em seguida poderia enviar o valor antigo.
        if (this.$refs.confirmadoInput) {
            this.$refs.confirmadoInput.value = '1';
        }

        this.confirmarAtraso = false;
        this.enviando = true;
        this.$refs.formAdd.submit();
    },

    aoEscape() {
        if (this.confirmarAtraso) {
            this.confirmarAtraso = false;

            return;
        }

        this.fechar();
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
    @keydown.escape.window="aoEscape()"
>

    <!-- Fundo Escuro / Backdrop -->
    <div x-show="modal" x-cloak x-transition.opacity @click.self="fechar()" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        
        {{-- 1. NOVO EMPRÉSTIMO --}}
        <div x-show="modal === 'add'" class="w-[64vw] max-h-[90vh] overflow-y-auto flex flex-col rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Novo Empréstimo</h2>
                
            <form x-ref="formAdd" :action="actionAdd" method="POST" class="flex flex-col gap-3 flex-1 min-h-0" @submit.prevent="enviarEmprestimo($event)">
                @csrf
                <input type="hidden" name="confirmado" value="0" x-ref="confirmadoInput">

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
                    <button type="submit" :disabled="enviando"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-text="enviando ? 'Registrando...' : 'Registrar Empréstimo'">Registrar Empréstimo</span>
                    </button>
                </div>
            </form>

            {{-- Confirmação de cautela: aluno com empréstimo em atraso --}}
            <div x-show="confirmarAtraso" x-cloak x-transition.opacity
                class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm"
                @click.self="confirmarAtraso = false">
                <div class="w-full max-w-md rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
                    <h2 class="text-lg font-bold mb-3">Empréstimo em atraso</h2>

                    <p>
                        O aluno <span class="font-bold" x-text="nomeAlunoSelecionado"></span>
                        possui empréstimo em atraso.
                    </p>

                    <p class="text-sm text-gray-300">Deseja registrar um novo empréstimo mesmo assim?</p>

                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" @click="confirmarAtraso = false" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20">Cancelar</button>
                        <button type="button" @click="confirmarEmprestimo()" :disabled="enviando"
                            class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-text="enviando ? 'Registrando...' : 'Confirmar empréstimo'">Confirmar empréstimo</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. REGISTRAR DEVOLUÇÃO --}}
        <div x-show="modal === 'devolver'" class="w-full max-w-md rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Registrar Devolução</h2>

            <form :action="actionDevolver" method="POST" class="flex flex-col gap-4" @submit="enviando = true">
                @csrf
                @method('PUT')

                <p>
                    Confirmar a devolução do livro 
                    <span class="font-bold" x-text="emprestimo.livro"></span> 
                    por <span class="font-bold" x-text="emprestimo.aluno"></span>?
                </p>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="fechar()" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20">Cancelar</button>
                    <button type="submit" :disabled="enviando"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-text="enviando ? 'Devolvendo...' : 'Devolver'">Devolver</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- 3. EXCLUIR REGISTRO DE DEVOLUÇÃO --}}
        <div x-show="modal === 'excluir'" class="w-full max-w-md rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Excluir Registro de Devolução</h2>

            <form :action="actionExcluir" method="POST" class="flex flex-col gap-4" @submit="enviando = true">
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
                    <button type="submit" :disabled="enviando"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-text="enviando ? 'Excluindo...' : 'Excluir'">Excluir</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- 4. EXCLUIR HISTÓRICO DE DEVOLUÇÕES --}}
        <div x-show="modal === 'excluir-historico'" class="w-full max-w-md rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Excluir Histórico de Devoluções</h2>

            <form :action="actionExcluirHistorico" method="POST" class="flex flex-col gap-4" @submit="enviando = true">
                @csrf
                @method('DELETE')

                <p>Excluir <span class="font-bold" x-text="totalDevolvidos"></span> registro(s) de devolução?</p>

                <p class="text-sm text-gray-300">Esta ação remove os registros antigos da lista. O estoque não será alterado.</p>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="fechar()" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20">Cancelar</button>
                    <button type="submit" :disabled="enviando"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-text="enviando ? 'Excluindo...' : 'Excluir tudo'">Excluir tudo</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>