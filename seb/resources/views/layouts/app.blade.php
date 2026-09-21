<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SEB</title>

    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="dark:bg-[#24272b] dark:text-white" wire:navigate.hover>

    
    {{ $slot }}
    
    @livewireScripts
</body>
</html>