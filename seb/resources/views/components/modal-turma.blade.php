<div x-data="{
    modal: null,
    turma: {},
    loading: false,
    baseUrl: '{{ url('/turmas') }}',

    abrir(tipoModal, dados = {}){
        this.modal = tipoModal;
        this.turma = dados.turma
            ? { ...dados.turma, cor: dados.turma.cor ?? '' }
            : {};

        if(tipoModal === 'add') {
            this.formAdd = { nome_turma: '', turno_turma: '', ano_turma: '', cor: '' };
        }
    },

    formAdd: {
        nome_turma: '',
        turno_turma: '',
        ano_turma: '',
        cor: ''
    },

    fechar(){this.modal = null;},
    get actionAdd()  { return `${this.baseUrl}`; },
    get actionEdit() { return `${this.baseUrl}/${this.turma.id}`; },
    get actionDel()  { return `${this.baseUrl}/${this.turma.id}`; },

   salvarTurma(event, dados) {
    const nome  = (dados.nome_turma ?? '').toString().trim();
    const turno = dados.turno_turma;
    const ano   = dados.ano_turma;

        if (!nome || !turno || !ano) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos Obrigatórios!',
                text: 'Por favor, preencha todos os campos antes de salvar a turma.',
                confirmButtonColor: '#3D2F2F',
                confirmButtonText: 'Entendi'
            });
            return;
        }

        this.loading = true;
        event.target.submit();
    }
}"

    @abrir-add-turma.window="abrir('add', $event.detail ?? {})"
    @abrir-edit-turma.window="abrir('edit', $event.detail ?? {})"
    @abrir-del-turma.window="abrir('del', $event.detail ?? {})"
    @keydown.escape.window="fechar()"
>

    <div x-show="modal" x-cloak x-transition.opacity @click.self="fechar()" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        {{-- adicionar --}}
        <div x-show="modal === 'add'" class="w-[64vw] max-h-[90vh] overflow-y-auto flex flex-col rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl" >
            <form :action="actionAdd" method="POST" class="flex flex-col flex-1 min-h-0" id='form-create' @submit.prevent="salvarTurma($event, formAdd)">
                @csrf

                <div class="flex flex-col gap-1 p-1.5">
                    <label for="nome_turma" class="font-bold">Nome da turma: <span class="text-white/40 font-normal">*</span></label>
                    <input type="text" name="nome_turma" id="nome_turma"
                    x-model="formAdd.nome_turma"
                    class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                </div>

                <div class="flex flex-col gap-1 p-1.5">
                    <label for="turno_turma" class="font-bold">Turno: <span class="text-white/40 font-normal">*</span></label>
                    <select name="turno_turma" id="turno_turma"
                    x-model="formAdd.turno_turma"
                    class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                        <option value="" class="text-black">Selecione o turno</option>
                        <option value="manha" class="text-black">Manhã</option>
                        <option value="tarde" class="text-black">Tarde</option>
                        <option value="noite" class="text-black">Noite</option>
                        <option value="integral" class="text-black">Integral</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1 p-1.5">
                    <label for="ano_turma" class="font-bold">Nível: <span class="text-white/40 font-normal">*</span></label>
                    <select name="ano_turma" id="ano_turma"
                    x-model="formAdd.ano_turma"
                    class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                        <option value="" class="text-black">Selecione o nível</option>
                        <option value="fundamentali" class="text-black">Fundamental I</option>
                        <option value="fundamentalii" class="text-black">Fundamental II</option>
                        <option value="em" class="text-black">Ensino Médio</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1 p-1.5">
                    <label for="cor_turma" class="font-bold">Cor da turma:</label>

                    <div class="flex flex-wrap items-center gap-2">
                        <input type="color" id="cor_turma"
                            :value="formAdd.cor || '#3D2F2F'"
                            @input="formAdd.cor = $event.target.value"
                            class="h-9 w-14 cursor-pointer rounded-md border border-white/20 bg-white/10 p-1">

                        <button type="button"
                            @click="formAdd.cor = ''"
                            class="rounded-md px-3 py-1.5 text-sm bg-white/10 hover:bg-white/20"
                            :class="formAdd.cor === '' ? 'ring-1 ring-white/60' : ''">
                            Padrão
                        </button>

                        <input type="hidden" name="cor" :value="formAdd.cor">

                        <span class="text-xs text-white/50">Sem cor, usa o padrão do tema claro/escuro.</span>
                    </div>
                </div>

                <div class="flex justify-end p-1.5">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    :disabled="loading"
                    :class="{ 'opacity-50 cursor-not-allowed': loading }"
                    >
                    <span  x-text="loading ? 'Salvando...' : 'Criar Turma'"></span>

                    </button>
                </div>

            </form>

        </div>

        {{-- edit --}}
        <div x-show="modal === 'edit'" class="w-[64vw] max-h-[90vh] overflow-y-auto flex flex-col rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <form :action="actionEdit" method="POST" class="flex flex-col flex-1 min-h-0" @submit="loading = true">
                @csrf
                @method('PUT')

                <div class="flex flex-col gap-1 p-1.5">
                    <label for="nome_turma" class="font-bold">Nome da turma: <span class="text-white/40 font-normal">*</span></label>
                    <input type="text" name="nome_turma" id="nome_turma"
                    class="rounded-md bg-white/10 border border-white/20 px-2 py-1"
                    x-model="turma.nome_turma"
                    >
                </div>

                <div class="flex flex-col gap-1 p-1.5">
                    <label for="turno_turma" class="font-bold">Turno: <span class="text-white/40 font-normal">*</span></label>
                    <select name="turno_turma" id="turno_turma" class="rounded-md bg-white/10 border border-white/20 px-2 py-1"
                    x-model="turma.turno_turma"
                    >
                        <option value="" class="text-black">Selecione o turno</option>
                        <option value="manha" class="text-black">Manhã</option>
                        <option value="tarde" class="text-black">Tarde</option>
                        <option value="noite" class="text-black">Noite</option>
                        <option value="integral" class="text-black">Integral</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1 p-1.5">
                    <label for="ano_turma" class="font-bold">Nível: <span class="text-white/40 font-normal">*</span></label>
                    <select name="ano_turma" id="ano_turma" class="rounded-md bg-white/10 border border-white/20 px-2 py-1"
                    x-model="turma.ano_turma"
                >
                        <option value="" class="text-black">Selecione o nível</option>
                        <option value="fundamentali" class="text-black">Fundamental I</option>
                        <option value="fundamentalii" class="text-black">Fundamental II</option>
                        <option value="em" class="text-black">Ensino Médio</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1 p-1.5">
                    <label for="cor_turma_edit" class="font-bold">Cor da turma:</label>

                    <div class="flex flex-wrap items-center gap-2">
                        <input type="color" id="cor_turma_edit"
                            :value="turma.cor || '#3D2F2F'"
                            @input="turma.cor = $event.target.value"
                            class="h-9 w-14 cursor-pointer rounded-md border border-white/20 bg-white/10 p-1">

                        <button type="button"
                            @click="turma.cor = ''"
                            class="rounded-md px-3 py-1.5 text-sm bg-white/10 hover:bg-white/20"
                            :class="! turma.cor ? 'ring-1 ring-white/60' : ''">
                            Padrão
                        </button>

                        <input type="hidden" name="cor" :value="turma.cor">

                        <span class="text-xs text-white/50">Sem cor, usa o padrão do tema claro/escuro.</span>
                    </div>
                </div>

                <div class="flex justify-end p-1.5">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    :disabled="loading"
                    :class="{ 'opacity-50 cursor-not-allowed': loading }"
                    >
                        <span  x-text="loading ? 'Salvando...' : 'Salvar Alterações'"></span>

                    </button>
                </div>
            </form>
        </div>

        {{-- deletar --}}
        <div x-show="modal === 'del'" class="w-full max-w-md rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Deletar turma</h2>
            <form :action="actionDel" method="POST" class="flex flex-col gap-4" @submit="loading = true">
                @csrf
                @method('DELETE')
                <p>Tem certeza que deseja deletar a turma <span class="font-bold" x-text="turma.nome_turma"></span>?</p>
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


</div>


@if ($errors->any())
    <div class="text-red-400 text-sm">{{ $errors->first() }}</div>
@endif
