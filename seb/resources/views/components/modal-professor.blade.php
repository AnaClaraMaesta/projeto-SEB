<div x-data="{
    modal: null,
    professor: {},
    baseUrl: '{{ url('/professores') }}',

    formAdd: {
        nome: '',
        email: '',
        telefone: '',
    },

    abrir(tipoModal, dados = {}) {
        this.modal = tipoModal;
        this.professor = dados.professor ?? {};

        if (tipoModal === 'add') {
            this.formAdd = {
                nome: '',
                email: '',
                telefone: '',
            };
        }
    },

    fechar() { this.modal = null; },

    get actionAdd()  { return `${this.baseUrl}`; },
    get actionEdit() { return `${this.baseUrl}/${this.professor.id}`; },
    get actionDel()  { return `${this.baseUrl}/${this.professor.id}`; }
}"
    @abrir-add-professor.window="abrir('add', $event.detail ?? {})"
    @abrir-edit-professor.window="abrir('edit', $event.detail ?? {})"
    @abrir-del-professor.window="abrir('del', $event.detail ?? {})"
    @keydown.escape.window="fechar()"
>

    <!-- Fundo Escuro / Backdrop -->
    <div x-show="modal" x-cloak x-transition.opacity @click.self="fechar()" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

        {{-- 1. ADICIONAR PROFESSOR --}}
        <div x-show="modal === 'add'" class="w-[64vw] max-h-[90vh] overflow-y-auto flex flex-col rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Novo Professor</h2>

            <form :action="actionAdd" method="POST" class="flex flex-col gap-3 flex-1 min-h-0">
                @csrf

                <div class="flex flex-col gap-1">
                    <label for="nome_add_professor" class="font-bold">Nome completo: <span class="text-white/40 font-normal">*</span></label>
                    <input type="text" name="nome" id="nome_add_professor" x-model="formAdd.nome" required
                           class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label for="email_add_professor" class="font-bold">E-mail: <span class="text-white/40 font-normal">*</span></label>
                        <input type="email" name="email" id="email_add_professor" x-model="formAdd.email" required
                               class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="telefone_add_professor" class="font-bold">Telefone: <span class="text-white/40 font-normal">*</span></label>
                        <input type="text" name="telefone" id="telefone_add_professor" x-model="formAdd.telefone" maxlength="11" required
                               class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                    </div>
                </div>

                <p class="text-xs text-white/60">
                    Associe o professor a uma ou mais turmas no cadastro/edição da turma.
                </p>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="fechar()" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20">Cancelar</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Cadastrar Professor
                    </button>
                </div>
            </form>
        </div>

        {{-- 2. EDITAR PROFESSOR --}}
        <div x-show="modal === 'edit'" class="w-[64vw] max-h-[90vh] overflow-y-auto flex flex-col rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Editar Professor</h2>

            <form :action="actionEdit" method="POST" class="flex flex-col gap-3 flex-1 min-h-0">
                @csrf
                @method('PUT')

                <div class="flex flex-col gap-1">
                    <label for="nome_edit_professor" class="font-bold">Nome completo: <span class="text-white/40 font-normal">*</span></label>
                    <input type="text" name="nome" id="nome_edit_professor" x-model="professor.nome" required
                           class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label for="email_edit_professor" class="font-bold">E-mail: <span class="text-white/40 font-normal">*</span></label>
                        <input type="email" name="email" id="email_edit_professor" x-model="professor.email" required
                               class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="telefone_edit_professor" class="font-bold">Telefone: <span class="text-white/40 font-normal">*</span></label>
                        <input type="text" name="telefone" id="telefone_edit_professor" x-model="professor.telefone" maxlength="11" required
                               class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                    </div>
                </div>

                <p class="text-xs text-white/60">
                    Associe o professor a uma ou mais turmas no cadastro/edição da turma.
                </p>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="fechar()" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20">Cancelar</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>

        {{-- 3. DELETAR PROFESSOR --}}
        <div x-show="modal === 'del'" class="w-full max-w-md rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Deletar Professor</h2>
            <form :action="actionDel" method="POST" class="flex flex-col gap-4">
                @csrf
                @method('DELETE')
                <p>Tem certeza que deseja deletar o professor <span class="font-bold" x-text="professor.nome"></span>?</p>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="fechar()" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20">Cancelar</button>
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Deletar
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
