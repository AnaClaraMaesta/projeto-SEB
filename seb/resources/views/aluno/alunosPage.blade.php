@extends('layouts.app')

@section('alunos')

<div class="flex m-h-screen w-full">
    <x-modal-turma />
    @include('layouts.navBar')

    <main class="flex-1">
        @yield('index')
    </main>
</div>
@endsection

