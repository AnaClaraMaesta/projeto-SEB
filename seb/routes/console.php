<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Notifica diariamente os usuários com empréstimos em atraso.
Schedule::command('emprestimos:notificar-atrasados')->dailyAt('08:00');
