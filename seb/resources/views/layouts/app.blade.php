<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SEB</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class=" dark:bg-[#24272b] dark:text-white">

        <div>
            @yield('dashboard')
        </div>
  
        <div class="flex justify-center align-center">
            @yield('login')
        </div>

        <div>
            @yield('cadastroUser')
        </div>

</body>
</html>