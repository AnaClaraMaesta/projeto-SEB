<?php

namespace App\Providers;

use App\Models\Aluno;
use App\Models\Livro;
use App\Models\Turma;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $view->with('turmasNav', Auth::check()
                ? Turma::where('user_id', Auth::id())->orderBy('nome_turma')->get()
                : collect());

            $view->with('alunosNav', Auth::check()
                ? Aluno::with('turma')->orderBy('nome')->get()
                : collect());

            // Apenas livros que podem ser emprestados (o último exemplar fica na biblioteca).
            $view->with('livrosNav', Auth::check()
                ? Livro::where('estoque', '>', 1)->orderBy('titulo')->get()
                : collect());
        });
    }
}
