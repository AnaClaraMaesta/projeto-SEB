@props(['turmas'])

<div x-data = "{
    modal:null,
    turmaId: '',
    turmas: @js($turmas->map(fn ($t) => ['id' => $t->id, 'nome_turma' => $t->nome_turma])->values()),
    aluno: {},
    baseUrl: '{{ url('/turmas') }}',
    csrfToken: '{{ csrf_token() }}',
    mostrarNovaTurma: false,
    salvandoTurma: false,
    turmaErro: '',
    novaTurma: { nome_turma: '', turno_turma: '', ano_turma: '' },

    abrir(tipoModal, dados = {}){
        this.modal = tipoModal;
        this.aluno = dados.aluno ?? {};
        this.turmaErro = '';
        this.mostrarNovaTurma = false;

        if (tipoModal === 'add') {
            this.turmaId = dados.turma != null ? String(dados.turma) : '';
            this.novaTurma = { nome_turma: '', turno_turma: '', ano_turma: '' };
        } else {
            this.turmaId = dados.turma != null ? String(dados.turma) : this.turmaId;
        }
    },
    fechar() {this.modal = null;},

    async criarTurma() {
        this.turmaErro = '';

        const dados = {
            nome_turma: (this.novaTurma.nome_turma ?? '').trim(),
            turno_turma: this.novaTurma.turno_turma,
            ano_turma: this.novaTurma.ano_turma,
        };

        if (!dados.nome_turma || !dados.turno_turma || !dados.ano_turma) {
            this.turmaErro = 'Preencha o nome, o turno e o nível da turma.';
            return;
        }

        this.salvandoTurma = true;

        try {
            const resposta = await fetch('{{ route('turma.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: JSON.stringify(dados),
            });

            if (resposta.status === 422) {
                const json = await resposta.json();
                this.turmaErro = Object.values(json.errors ?? {}).flat()[0] ?? 'Dados inválidos.';
                return;
            }

            if (!resposta.ok) {
                this.turmaErro = 'Não foi possível criar a turma. Tente novamente.';
                return;
            }

            const turma = await resposta.json();

            this.turmas.push(turma);
            this.turmas.sort((a, b) => a.nome_turma.localeCompare(b.nome_turma));
            this.turmaId = String(turma.id);
            this.novaTurma = { nome_turma: '', turno_turma: '', ano_turma: '' };
            this.mostrarNovaTurma = false;

            Swal.fire({
                icon: 'success',
                title: 'Turma criada!',
                text: 'A turma já foi selecionada para o novo aluno.',
                timer: 1800,
                showConfirmButton: false,
            });
        } catch (erro) {
            this.turmaErro = 'Falha de conexão ao criar a turma.';
        } finally {
            this.salvandoTurma = false;
        }
    },

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
                
            <form :action="actionAdd" method="POST" class="flex flex-col flex-1 min-h-0" @submit="loading = true">
                @csrf

                <div class="flex flex-col gap-1">
                    <label class="font-bold">Turma:</label>

                    <div class="flex items-center gap-2">
                        <select x-model="turmaId" required
                            class="flex-1 rounded-md bg-white/10 border border-white/20 px-2 py-1">
                            <option value="" disabled class="text-black">Selecione...</option>
                            <template x-for="t in turmas" :key="t.id">
                                <option :value="String(t.id)" x-text="t.nome_turma" class="text-black"></option>
                            </template>
                        </select>

                        <button type="button"
                            @click="mostrarNovaTurma = !mostrarNovaTurma; turmaErro = ''"
                            class="shrink-0 h-9 w-9 rounded-md bg-green-600 hover:bg-green-700 text-white text-xl font-bold leading-none cursor-pointer"
                            title="Criar nova turma"
                            aria-label="Criar nova turma">
                            +
                        </button>
                    </div>

                    {{-- Atalho para criar uma turma sem sair do cadastro do aluno --}}
                    <div x-show="mostrarNovaTurma" x-transition
                        class="mt-2 rounded-md border border-white/20 bg-black/10 p-3">
                        <p class="text-sm font-semibold mb-2">Nova turma</p>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <input type="text" x-model="novaTurma.nome_turma" placeholder="Nome da turma"
                                class="rounded-md bg-white/10 border border-white/20 px-2 py-1">

                            <select x-model="novaTurma.turno_turma"
                                class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                                <option value="" class="text-black">Turno</option>
                                <option value="manha" class="text-black">Manhã</option>
                                <option value="tarde" class="text-black">Tarde</option>
                                <option value="noite" class="text-black">Noite</option>
                                <option value="integral" class="text-black">Integral</option>
                            </select>

                            <select x-model="novaTurma.ano_turma"
                                class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                                <option value="" class="text-black">Nível</option>
                                <option value="fundamentali" class="text-black">Fundamental I</option>
                                <option value="fundamentalii" class="text-black">Fundamental II</option>
                                <option value="em" class="text-black">Ensino Médio</option>
                            </select>
                        </div>

                        <p x-show="turmaErro" x-text="turmaErro" class="text-red-400 text-xs mt-2"></p>

                        <div class="flex justify-end gap-2 mt-2">
                            <button type="button"
                                @click="mostrarNovaTurma = false; turmaErro = ''"
                                class="px-3 py-1.5 rounded bg-white/10 hover:bg-white/20 text-sm">
                                Cancelar
                            </button>
                            <button type="button" @click="criarTurma()" :disabled="salvandoTurma"
                                class="px-3 py-1.5 rounded bg-green-600 hover:bg-green-700 text-white text-sm font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-text="salvandoTurma ? 'Criando...' : 'Criar turma'"></span>
                            </button>
                        </div>
                    </div>
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
            <form :action="actionEdit" method="POST" @submit="loading = true">
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
                        <option value="Atrasado" class="text-black">Em atraso</option>
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
            <form :action="actionDel" method="POST" class="flex flex-col gap-4" @submit="loading = true">
                @csrf
                @method('DELETE')
                <p>Tem certeza que deseja deletar o aluno <span class="font-bold" x-text="aluno.nome"></span>?</p>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="fechar()" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20">Cancelar</button>
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="loading">
                        <span x-text="loading ? 'Excluindo...' : 'Deletar'">Deletar</span>
                    </button>
                </div>
            </form>
        </div>

</div>