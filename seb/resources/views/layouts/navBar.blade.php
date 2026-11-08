<div  x-data="{ open_nav: true }" class="flex">

    <header
        :class="open_nav ? 'w-56' : 'w-14'"
        class="sticky top-0 h-screen shrink-0 flex flex-col overflow-hidden
        bg-[#3D2F2F] text-white dark:bg-[#464a4f] shadow-md
        transition-all duration-300">

        <div class="p-2 flex" :class="open_nav ? 'justify-end' : 'justify-center'">
            <button @click="open_nav = !open_nav"
                    aria-label="Mostrar ou esconder menu"
                    class="p-2 rounded-md cursor-pointer hover:bg-[#2F3D3D]">
                <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                    <path fill="currentColor" fill-rule="evenodd" d="M3.5 5a1 1 0 0 0 0 2h17a1 1 0 1 0 0-2zm-1 7a1 1 0 0 1 1-1h17a1 1 0 1 1 0 2h-17a1 1 0 0 1-1-1m0 6.001a1 1 0 0 1 1-1h17a1 1 0 1 1 0 2h-17a1 1 0 0 1-1-1" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
        
        <div x-show="open_nav" x-cloak x-transition.opacity
          class="w-56 flex flex-col gap-2 whitespace-nowrap">
        
          <div x-data="{ open_user: false }" class="relative p-2" >
                     <button @click="open_user = !open_user" class="btn cursor-pointer p-3 w-full items-center justify-between gap-3 flex">
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                         </svg>

         
                          <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open_user}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                         </svg>
         
                     </button>
                    <div class="text-bold opacity-75">{{ auth()->user()->name }}</div>

                     <div
                         x-show="open_user"
                         @click.away="open_user = false"
                         x-transition
                         class="rounded-md m-2"
                     >
                         <div class="block w-full p-2 rounded-md hover:bg-[#2F3D3D]">
                             <button class="btn cursor-pointer border-gray-500 hover:">
                                 EDITAR
                             </button>
                         </div>
                     </div>
                 </div>
         
             <div class="flex flex-col gap-1 p-2">
                 <div  x-data="{ open_alunos: false}" class="relative">
                 
                    
                    <button type="button" @click="open_alunos = !open_alunos"
                            class="cursor-pointer p-3 w-full flex items-center justify-between hover:bg-[#2F3D3D] rounded-md">
                        ALUNOS
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open_alunos}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                     
                     <div x-show="open_alunos" x-cloak x-transition
                            @click.outside="open_alunos = false"
                            class="m-2 bg-[#3D2F2F] dark:bg-[#464a4f]">

                            <div class="p-0.5">
                                <a href="#"
                                @click.prevent="$dispatch('abrir-add-aluno'); open_alunos = false"
                                class="block w-full p-2 rounded-md hover:bg-[#2F3D3D]">
                                    CADASTRAR
                                </a>
                            </div>

                            <div class="p-0.5"> 
                             <a href="{{ route('aluno.index') }}" 
                             {{-- @click.prevent="$dispatch('abrir-consultar-aluno'); open_alunos = false" --}}
                                class="block w-full p-2 rounded-md hover:bg-[#2F3D3D]">
                                 CONSULTAR
                             </a>
                         </div>
                     </div>
                 </div>

                <div  x-data="{ open_turma: false}" class="relative">
                 
                    
                    <button type="button" @click="open_turma = !open_turma"
                            class="cursor-pointer p-3 w-full flex items-center justify-between hover:bg-[#2F3D3D] rounded-md">
                        TURMA
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open_turma}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                     <div x-show="open_turma" x-cloak x-transition
                            @click.outside="open_turma = false"
                            class="m-2 bg-[#3D2F2F] dark:bg-[#464a4f]">

                            <div class="p-0.5">
                                <a href="#"
                                @click.prevent="$dispatch('abrir-add-turma'); open_turma = false"
                                class="block w-full p-2 rounded-md hover:bg-[#2F3D3D]">
                                    CADASTRAR
                                </a>
                            </div>

                            <div class="p-0.5"> 
                             <a href="{{ route('turma.index') }}" 
                             {{-- @click.prevent="$dispatch('abrir-consultar-turma'); open_turma = false" --}}
                            class="block w-full p-2 rounded-md hover:bg-[#2F3D3D]">
                                 CONSULTAR
                             </a>
                         </div>
                     </div>
                 </div>
         
                 <div x-data="{ open_livro: false }" class="relative">
         
                     <button @click="open_livro = !open_livro" 
                        class="cursor-pointer p-3 w-full flex items-center justify-between hover:bg-[#2F3D3D] rounded-md">
                         LIVROS  
                         
                         <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open_livro}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                         </svg>
                     </button>
         
                     <div
                         x-show="open_livro" 
                         @click.away="open_livro = false"
                         x-transition
                         class="m-2 bg-[#3D2F2F] dark:bg-[#464a4f]"
                        >
                        <div class="p-0.5">
                                <a href="#"
                                @click.prevent="$dispatch('abrir-add-livro'); open_livro = false"
                                class="block w-full p-2 rounded-md hover:bg-[#2F3D3D]">
                                    CADASTRAR
                                </a>
                            </div>

                            <div class="p-0.5"> 
                             <a href="{{ route('livro.index') }}" 
                             {{-- @click.prevent="$dispatch('abrir-consultar-livro'); open_livro = false" --}}
                            class="block w-full p-2 rounded-md hover:bg-[#2F3D3D]">
                                 CONSULTAR
                             </a>
                         </div>
                     </div>
                 </div>
         
             </div>
         </div>
             
    </header>
</div>