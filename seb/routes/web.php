<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\Auth\EsqueciSenhaController;
use App\Http\Controllers\Auth\ResetSenhaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TurmaController;
use App\Http\Controllers\LivroController;
use App\Services\Login;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//passar as rotas de senha para ca quando o logout estiver pronto
Route::middleware('guest')->group(function () {
        
    Route::post('/forgot-password', [EsqueciSenhaController::class, 'enviarLinkResetEmail'])->name('password.email');
    Route::get('forgot-password', [EsqueciSenhaController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/reset-password', [ResetSenhaController::class, 'resetSenha'])->name('password.update');   

});
    
// rotas que n precisam necessariamente do email autenticado mas o usuario precisa existir
Route::middleware('auth')->group(function(){
    Route::get('/email/verify', fn() => view('email.VerificacaoEmail')) -> name('verification.notice');
});
    
    
    //rota que faz a verificação do email
Route::middleware(['auth', 'signed'])->group(function () {
    Route::get('/email/verify/{id}/{hash}', function(EmailVerificationRequest $request) { 
    $request->fulfill();
    return redirect('/login'); }) ->name('verification.verify');
});
        
//gera uma url temporaria
Route::middleware(['signed'])->group(function () {
    Route::get('/reset-password/{token}', [ResetSenhaController::class, 'showResetForm'])->name('password.reset');            
});

//rotas para quando é necessario o email estar autenticado
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/turmas/pdf', [turmaController::class, 'gerarPDF'])->name('turma.pdf');
    Route::post('/turmas', [turmaController::class, 'store'])->name('turma.store');
    Route::get('/turmas/{turma}', [turmaController::class, 'show'])->name('turma.show');
    Route::put('/turmas/{turma}', [turmaController::class, 'update'])->name('turma.update');
    Route::delete('/turmas/{turma}', [turmaController::class, 'destroy'])->name('turma.destroy');
    Route::get('/turmas', [turmaController::class, 'index'])->name('turma.index');

    Route::get('alunos/pdf', [alunoController::class, 'gerarPDFGeral'])->name('aluno.pdfGeral');
    Route::get('/turmas/{turma}/aluno/pdf', [alunoController::class, 'gerarPDF'])->name('aluno.pdf');
    Route::get('/alunos', [alunoController::class, 'index'])->name('aluno.index');
    Route::post('/turmas/{turma}/aluno', [alunoController::class, 'store'])->name('aluno.store');
    Route::put('/turmas/{turma}/aluno/{aluno}', [alunoController::class, 'update'])->name('aluno.update');
    Route::delete('/turmas/{turma}/aluno/{aluno}', [alunoController::class, 'destroy'])->name('aluno.destroy');

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




Route::get('/email/verify', fn() => view('email.VerificacaoEmail')) -> name('verification.notice');

/* Login */
Route::get('/login',[loginController::class, 'index']) -> name('login');
Route::post('/login',[loginController::class, 'store']) -> name('login.store');

/* Usuario */
Route::get('/usuario/create', [userController::class, 'create'])->name('usuarios.create');
Route::post('/usuario/create', [userController::class, 'store']) -> name('usuarios.store');
Route::get('/usuario/{usuario}', [UserController::class, 'edit'])->name('usuarios.edit');
Route::put('/usuario/{usuario}', [userController::class, 'update'])->name('usuarios.update');
Route::delete('/usuario/{usuario}', [userController::class, 'destroy'])->name('usuarios.destroy');
Route::get('/logout', [UserController::class, 'logout'])->middleware('auth')->name('logout');   