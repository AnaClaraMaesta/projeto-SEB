@extends('layouts.app')

@section('turmas')
<div class="flex m-h-screen w-full">
    @include('turma.turmasHeader')

    <main class="flex-1">
        @yield('turmasBody')
    </main>
</div>
@endsection

