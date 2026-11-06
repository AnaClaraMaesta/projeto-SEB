<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SEB</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-white dark:bg-[#24272b] dark:text-white">
        
            <div class="flex">
                @yield('login')
                @yield('createUser')
            </div>

            <div class="flex">
                 @yield('forgot-password')
                 @yield('reset-password')
            </div>
     @auth
        <x-modal-aluno :turmas="$turmasNav" />
        @yield('turmas')
        @yield('alunos')
        @yield('livros')
    @endauth
    @stack('scripts')
</body>
</html>
