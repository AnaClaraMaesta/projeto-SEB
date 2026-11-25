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
                capa_url: '',
                estoque: 1,
            };
        }
    },

    formAdd: {
        titulo: '',
        autor: '',
        ano_publicacao: '',
        codigo: '',
        capa_url: '',
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

                <div class="flex flex-col gap-1">
                    <label for="capa_add" class="font-bold">Capa do livro <span class="text-white/40 font-normal">(URL da imagem, opcional)</span>:</label>

                    <div class="flex items-start gap-3">
                        <input type="url" name="capa_url" id="capa_add" x-model="formAdd.capa_url"
                               placeholder="https://exemplo.com/capa.jpg"
                               class="flex-1 rounded-md bg-white/10 border border-white/20 px-2 py-1">

                        <div class="h-20 w-14 shrink-0 overflow-hidden rounded border border-white/20 bg-black/20 flex items-center justify-center">
                            <template x-if="formAdd.capa_url">
                                <img :src="formAdd.capa_url" alt="Prévia da capa" class="h-full w-full object-cover">
                            </template>
                            <template x-if="! formAdd.capa_url">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-6 h-6 text-white/40" viewBox="0 0 16 16">
                                    <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                                </svg>
                            </template>
                        </div>
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

                <div class="flex flex-col gap-1">
                    <label for="capa_edit" class="font-bold">Capa do livro <span class="text-white/40 font-normal">(URL da imagem, opcional)</span>:</label>

                    <div class="flex items-start gap-3">
                        <input type="url" name="capa_url" id="capa_edit" x-model="livro.capa_url"
                               placeholder="https://exemplo.com/capa.jpg"
                               class="flex-1 rounded-md bg-white/10 border border-white/20 px-2 py-1">

                        <div class="h-20 w-14 shrink-0 overflow-hidden rounded border border-white/20 bg-black/20 flex items-center justify-center">
                            <template x-if="livro.capa_url">
                                <img :src="livro.capa_url" alt="Prévia da capa" class="h-full w-full object-cover">
                            </template>
                            <template x-if="! livro.capa_url">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-6 h-6 text-white/40" viewBox="0 0 16 16">
                                    <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"/>
                                </svg>
                            </template>
                        </div>
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