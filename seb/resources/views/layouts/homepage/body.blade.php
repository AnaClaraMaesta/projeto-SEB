
<div class="bg-[#3D2F2F] text-white dark:bg-[#464a4f]">
    <div class="grid grid-3">
        <div class="justify-start m-3" x-data="{ add_turma: false }"  >
            
            <button @click="add_turma = !add_turma"  class="btn cursor-pointer bg-[#3D2F2F] hover:bg-[#2F3D3D] w-30 rounded-md">
                Adicionar turma
            </button>

            <div 
                x-show="add_turma" 
                @click.away="add_turma = false"
                x-transition
                class="m-2 rounded-md bg-[#3D2F2F] dark:bg-[#464a4f] h-96 w-96"
            >
                <form action="" class="">
                    <div>
                        <label for="nome_turma">Nome da turma:</label>
                        <input type="text" name="nome_turma" id="nome_turma" class="rounded-md text-black">
                    </div>

                    <div>
                        <label for="turno_turma">Turno da turma:</label>
                        <input type="text" name="turno_turma" id="turno_turma" class="rounded-md text-black">
                    </div>
                    
                    <div>
                        <label for="ano_turma">Ano da turma:</label>
                        <input type="text" name="ano_turma" id="ano_turma" class="rounded-md text-black">
                    </div>
                    


                    <div>
                        <select name="professor_turma" id="professor_turma" class="rounded-md text-black">
                            <option value="">Selecione um professor</option>
                            <option value="p1">Professor 1</option>
                            <option value="p2">Professor 2</option>
                            <option value="p3">Professor 3</option>
                        </select>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
