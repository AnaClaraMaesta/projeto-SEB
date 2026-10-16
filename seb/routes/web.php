<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\turmaController;
use App\Services\login;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Route::get('/turmas',[turmaController::class, 'index']) -> name('turmas'); //depois de configurar o controller

Route::middleware(['auth', 'verified'])->group(function () {

Route::get('/email/verify/{id}/{hash}', fn(EmailVerificationRequest $request)=> $request->fulfill -> redirect('/login'))->name('verification.verify');


});



Route::get('/turmas',[turmaController::class, 'index']) -> name('turmas');
Route::post('/turmas', [turmaController::class, 'store'])->name('turma.store');
Route::get('/turmas/{turma}', [turmaController::class, 'show'])->name('turma.show');
Route::put('/turmas/{turma}', [turmaController::class, 'update'])->name('turma.update');
Route::delete('/turmas/{turma}', [turmaController::class, 'destroy'])->name('turma.destroy');


Route::get('/login',[loginController::class, 'index']) -> name('login');
Route::post('/login',[loginController::class, 'store']) -> name('login.store');



Route::get('/usuario/create', [userController::class, 'index'])->name('usuarios.create');
Route::post('/usuario/create', [userController::class, 'store']) -> name('usuarios.store');
/* Route::get('URL', [nome do Controller::class, 'nome da função do controller']) -> definir nome para identificar como rota name('usuarios.cadastro'); */

