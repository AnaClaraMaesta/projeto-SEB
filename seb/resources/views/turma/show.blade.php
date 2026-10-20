@extends('turma.turmasPage')
@section('show')

<div class="bg-white dark:bg-[#24272b] text-black min-h-screen p-3 dark:bg-[#24272b] dark:text-white">
    <h1 class="text-2xl font-bold">TURMA {{ $turma->nome }}</h1>
</div>

@endsection