<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\Auth\loginController;
use Livewire\Volt\Volt;

Route::get('/dashboard', function(){
    return view('dashboard');
});

Volt::route('/login', 'auth.login')->name('login');

Route::get('/criar-usuario', [userController::class, 'create']) -> name('usuarios.create');

/* Route::get('URL', [nome do Controller::class, 'nome da função do controller']) -> definir nome para identificar como rota name('usuarios.cadastro'); */
