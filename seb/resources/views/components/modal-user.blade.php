<div x-data="{
    modal: null,
    userId: '',
    user: {},
    baseUrl: '{{ url('/usuario') }}',

    abrir(tipoModal, dados = {}){
        this.modal = tipoModal;
        this.user = dados.user ?? {};
    },
        
    fechar(){this.modal = null;},
    get actionAdd()  { return `${this.baseUrl}`; },
    get actionEdit() { return `${this.baseUrl}/${this.user.id}`; },
    get actionDel()  { return `${this.baseUrl}/${this.user.id}`; },
    
   salvarUser(event, dados) {
    const name  = (dados.name ?? '').toString().trim();
    const email = (dados.email ?? '').toString().trim();

        if (!name || !email) {
            Swal.fire({
                icon: 'warning',
                title: 'Campos Obrigatórios!',
                text: 'Por favor, preencha todos os campos antes de salvar o usuário.',
                confirmButtonColor: '#3D2F2F',
                confirmButtonText: 'Entendi'
            });
            return;
        }
        event.target.submit();
    }
}">

</div>
