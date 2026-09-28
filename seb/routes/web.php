<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\Auth\loginController;
use App\Http\Controllers\turmaController;


Route::get('/turmas',[turmaController::class, 'index']) -> name('turmas');
// Route::get('dashboard/{turma}', function ($turma) {
//     return view('layouts.dashboard');
// });

Route::get('/login',[loginController::class, 'index']) -> name('login');

Route::post('/login',[loginController::class, 'store']) -> name('login.store');

Route::get('/usuario/create', [userController::class, 'index'])->name('usuarios.create');

Route::post('/usuario/create', [userController::class, 'store']) -> name('usuarios.store');
/* Route::get('URL', [nome do Controller::class, 'nome da função do controller']) -> definir nome para identificar como rota name('usuarios.cadastro'); */
