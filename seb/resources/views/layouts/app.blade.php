<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SEB</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="dark:bg-[#24272b] dark:text-white ">
 <div class="flex h-full">

        @yield('dashboard')

        <main class="flex-1 overflow-y-auto">

            <div class="flex justify-start p-5">
                @yield('login')
            </div>

            <div class="flex justify-center p-5">
                @yield('createUser')
            </div>

        </main>

    </div>
</body>
</html>