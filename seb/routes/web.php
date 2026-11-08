<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\Auth\EsqueciSenhaController;
use App\Http\Controllers\Auth\ResetSenhaController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TurmaController;
use App\Http\Controllers\UserController;
use App\Services\Login;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/turmas',[TurmaController::class, 'index']) -> name('turma.index'); //depois de configurar o controller

// passar as rotas de senha para ca quando o logout estiver pronto
Route::middleware('guest')->group(function () {});

Route::post('/forgot-password', [EsqueciSenhaController::class, 'enviarLinkResetEmail'])->name('password.email');
Route::get('forgot-password', [EsqueciSenhaController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/reset-password', [ResetSenhaController::class, 'resetSenha'])->name('password.update');

// rotas que n precisam necessariamente do email autenticado mas o usuario precisa existir
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', fn () => view('email.VerificacaoEmail'))->name('verification.notice');
});

    // rota que faz a verificação do email
Route::middleware(['auth', 'signed'])->group(function () {
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect('/login');
    })->name('verification.verify');
});

// gera uma url temporaria
Route::middleware(['signed'])->group(function () {
    Route::get('/reset-password/{token}', [ResetSenhaController::class, 'showResetForm'])->name('password.reset');
});

// rotas para quando é necessario o email estar autenticado
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/turmas', [TurmaController::class, 'index'])->name('turma.index');
});

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'verification-link-sent');
})->middleware('throttle:6,1')->name('verification.send');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

Route::get('/email/verify', fn () => view('email.VerificacaoEmail'))->name('verification.notice');

/* Turmas */
Route::get('/turmas/pdf', [TurmaController::class, 'gerarPDF'])->name('turma.pdf');
Route::post('/turmas', [TurmaController::class, 'store'])->name('turma.store');
Route::get('/turmas/{turma}', [TurmaController::class, 'show'])->name('turma.show');
Route::put('/turmas/{turma}', [TurmaController::class, 'update'])->name('turma.update');
Route::delete('/turmas/{turma}', [TurmaController::class, 'destroy'])->name('turma.destroy');

/* Login */
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

/* Usuario */
Route::get('/usuario/create', [UserController::class, 'create'])->name('usuarios.create');
Route::post('/usuario/create', [UserController::class, 'store'])->name('usuarios.store');
/* Route::get('URL', [nome do Controller::class, 'nome da função do controller']) -> definir nome para identificar como rota name('usuarios.cadastro'); */

/* Alunos */
Route::get('alunos/pdf', [AlunoController::class, 'gerarPDFGeral'])->name('aluno.pdfGeral');
Route::get('/turma/{turma}/aluno/pdf', [AlunoController::class, 'gerarPDF'])->name('aluno.pdf');
Route::get('/alunos', [AlunoController::class, 'index'])->name('aluno.index');
Route::post('/turmas/{turma}/aluno', [AlunoController::class, 'store'])->name('aluno.store');
Route::put('/turmas/{turma}/aluno/{aluno}', [AlunoController::class, 'update'])->name('aluno.update');
Route::delete('/turmas/{turma}/aluno/{aluno}', [AlunoController::class, 'destroy'])->name('aluno.destroy');

/* Livros */
Route::get('/livros', [LivroController::class, 'index'])->name('livro.index');
Route::get('/livros/pdf', [LivroController::class, 'gerarPDF'])->name('livro.pdf');
Route::post('/livros', [LivroController::class, 'store'])->name('livro.store');
Route::put('/livros/{livro}', [LivroController::class, 'update'])->name('livro.update');
Route::delete('/livros/{livro}', [LivroController::class, 'destroy'])->name('livro.destroy');
