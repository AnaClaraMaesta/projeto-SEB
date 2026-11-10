@extends('layouts.app')
@section('logout')

<div class="flex min-h-screen items-center justify-center p-5">
    <div class="w-full max-w-md rounded-2xl bg-[#3D2F2F] dark:bg-[#464a4f] text-white p-8 shadow-2xl ring-1 ring-white/10">
        <h1 class="text-xl font-bold">Sair da conta</h1>
        <p class="mt-2 text-sm text-white/70">Tem certeza que deseja sair?</p>

        <form method="POST" action="{{ route('logout') }}" class="mt-6 flex justify-end gap-3">
            @csrf
            <a href="{{ url()->previous() }}"
               class="rounded-lg px-4 py-2.5 text-sm font-medium text-white/70 hover:text-white hover:bg-white/10 transition">
                Cancelar
            </a>
            <button type="submit"
                    class="rounded-lg bg-red-500 hover:bg-red-700 border border-white/20 px-6 py-2.5 text-sm font-semibold text-white transition cursor-pointer">
                Sair
            </button>
        </form>
    </div>
</div>
@endsection