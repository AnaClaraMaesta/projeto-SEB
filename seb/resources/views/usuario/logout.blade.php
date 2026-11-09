

<div class="w-full max-w-md rounded-xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-6 shadow-2xl" 
    x-show="logout" x-cloak x-transition.opacity >
                                
    <form method="POST" action="{{ route('logout') }}">
            @csrf
            <p>Tem certeza que deseja deletar a turma <span class="font-bold" x-text="turma.nome_turma"></span>?</p>
            <button type="button" class="px-4 py-2 rounded bg-white/10 hover:bg-white/20">Cancelar</button>
            <button type="submit" class="text-red-600 cursor-pointer">
            
                Prosseguir
            </button>
    </form>
</div>