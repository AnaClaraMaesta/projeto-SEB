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
    
    @yield('turmas')
    @yield('alunos')
        
    <div class="flex">  
        
        <main class="flex-1 overflow-y-auto">
            
            <div class="flex justify-start p-5">
                @yield('login')
                @yield('createUser')
            </div>

            <div class="flex justify-center mt-20">
                 @yield('forgot-password')
                 @yield('reset-password')
            </div>

        </main>
        
    </div>
</body>
</html>