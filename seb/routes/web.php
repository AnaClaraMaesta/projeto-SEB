<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\turmaController;
use App\Services\login;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Route::get('/turmas',[turmaController::class, 'index']) -> name('turmas'); //depois de configurar o controller



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

//rotas para quando é necessario o email estar autenticado
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/turmas',[turmaController::class, 'index']) -> name('turmas');


  
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







Route::post('/turmas', [turmaController::class, 'store'])->name('turma.store');
Route::get('/turmas/{turma}', [turmaController::class, 'show'])->name('turma.show');

Route::get('/login',[loginController::class, 'index']) -> name('login');
Route::post('/login',[loginController::class, 'store']) -> name('login.store');


Route::get('/usuario/create', [userController::class, 'index'])->name('usuarios.create');
Route::post('/usuario/create', [userController::class, 'store']) -> name('usuarios.store');
/* Route::get('URL', [nome do Controller::class, 'nome da função do controller']) -> definir nome para identificar como rota name('usuarios.cadastro'); */

