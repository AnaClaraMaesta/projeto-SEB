@extends('layouts.app')

@section('turmas')

<div class="flex m-h-screen w-full">
    <x-modal-turma />
    @include('layouts.navBar')

    <main class="flex-1">
        @yield('dashboard')
        @yield('show')
    </main>
</div>
@endsection

