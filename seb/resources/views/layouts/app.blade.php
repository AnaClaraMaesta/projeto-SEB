<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SEB</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="dark:bg-[#24272b] dark:text-white ">
        
        @yield('dashboard')
  
        <div class="h-full flex justify-left p-5">
            @yield('login')
        </div>

        <div class="h-full flex justify-center p-5">
            @yield('createUser')
        </div>

</body>
</html>