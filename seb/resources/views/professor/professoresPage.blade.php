@extends('layouts.app')

@section('professores')

<div class="flex m-h-screen w-full">
    @include('layouts.navBar')

    <main class="flex-1">
        @yield('index')
    </main>
</div>
@endsection
