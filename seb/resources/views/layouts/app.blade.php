<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @if () {{-- se tiver validado como usuário e entrar com os cookies salvos --}}
        <div class='bg-light py-4 d-flex flex-col'>
            @yield('dashboard')
        </div>
    @endif

   @if () {{-- padrao deslogado --}} 
        <div class='d-flex justify-content-center align-items-center'>
            @yield('login')
        </div>
   @endif

</body>
</html>