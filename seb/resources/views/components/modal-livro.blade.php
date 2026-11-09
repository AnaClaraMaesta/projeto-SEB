<div x-data="{
    modal: null,
    livro: {},
    baseUrl: '{{ url('/livros') }}',

    abrir(tipoModal, dados = {}) {
        this.modal = tipoModal;
        this.livro = dados.livro ?? {};

        if (tipoModal === 'add') {
            this.formAdd = {
                titulo: '',
                autor: '',
                ano_publicacao: '',
                codigo: '',
                estoque: 1,
            };
        }
    },

    formAdd: {
        titulo: '',
        autor: '',
        ano_publicacao: '',
        codigo: '',
        estoque: 1,
    },

    fechar() { this.modal = null; },

    get actionAdd()  { return `${this.baseUrl}`; },
    get actionEdit() { return `${this.baseUrl}/${this.livro.id}`; },
    get actionDel()  { return `${this.baseUrl}/${this.livro.id}`; }
}"
    @abrir-add-livro.window="abrir('add', $event.detail ?? {})"
    @abrir-edit-livro.window="abrir('edit', $event.detail ?? {})"
    @abrir-del-livro.window="abrir('del', $event.detail ?? {})"
    @keydown.escape.window="fechar()"
>

    <!-- Fundo Escuro / Backdrop -->
    <div x-show="modal" x-cloak x-transition.opacity @click.self="fechar()" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        
        {{-- 1. ADICIONAR LIVRO --}}
        <div x-show="modal === 'add'" class="w-[64vw] max-h-[90vh] overflow-y-auto flex flex-col rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Novo Livro</h2>
                
            <form :action="actionAdd" method="POST" class="flex flex-col gap-3 flex-1 min-h-0">
                @csrf

                <div class="flex flex-col gap-1">
                    <label for="titulo_add" class="font-bold">Título:</label>
                    <input type="text" name="titulo" id="titulo_add" x-model="formAdd.titulo" required
                           class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label for="autor_add" class="font-bold">Autor:</label>
                        <input type="text" name="autor" id="autor_add" x-model="formAdd.autor" required
                               class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="codigo_add" class="font-bold">Código:</label>
                        <input type="text" name="codigo" id="codigo_add" x-model="formAdd.codigo"
                               class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label for="ano_add" class="font-bold">Ano de Publicação:</label>
                        <input type="number" name="ano_publicacao" id="ano_add" x-model="formAdd.ano_publicacao"
                               class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="estoque_add" class="font-bold">Estoque:</label>
                        <input type="number" name="estoque" id="estoque_add" x-model="formAdd.estoque" min="0" required
                               class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="fechar()" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20">Cancelar</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Cadastrar Livro
                    </button>
                </div>
            </form>
        </div>

        {{-- 2. EDITAR LIVRO --}}
        <div x-show="modal === 'edit'" class="w-[64vw] max-h-[90vh] overflow-y-auto flex flex-col rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Editar Livro</h2>
                
            <form :action="actionEdit" method="POST" class="flex flex-col gap-3 flex-1 min-h-0">
                @csrf
                @method('PUT')

                <div class="flex flex-col gap-1">
                    <label for="titulo_edit" class="font-bold">Título:</label>
                    <input type="text" name="titulo" id="titulo_edit" x-model="livro.titulo" required
                           class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label for="autor_edit" class="font-bold">Autor:</label>
                        <input type="text" name="autor" id="autor_edit" x-model="livro.autor" required
                               class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="codigo_edit" class="font-bold">Código:</label>
                        <input type="text" name="codigo" id="codigo_edit" x-model="livro.codigo"
                               class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1">
                        <label for="ano_edit" class="font-bold">Ano de Publicação:</label>
                        <input type="number" name="ano_publicacao" id="ano_edit" x-model="livro.ano_publicacao"
                               class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="estoque_edit" class="font-bold">Estoque:</label>
                        <input type="number" name="estoque" id="estoque_edit" x-model="livro.estoque" min="0" required
                               class="rounded-md bg-white/10 border border-white/20 px-2 py-1">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="fechar()" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20">Cancelar</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>

        {{-- 3. DELETAR LIVRO --}}
        <div x-show="modal === 'del'" class="w-full max-w-md rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl">
            <h2 class="text-lg font-bold mb-3">Deletar Livro</h2>
            <form :action="actionDel" method="POST" class="flex flex-col gap-4">
                @csrf
                @method('DELETE')
                <p>Tem certeza que deseja deletar o livro <span class="font-bold" x-text="livro.titulo"></span>?</p>
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