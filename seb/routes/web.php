<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
Route::get('/', function () {
    return view('layouts.login');
});

Route::get('/dashboard', function(){
    return view('layouts.homePage');
});



Route::get('/cadastroUser', [userController::class, 'index']) -> name('usuarios.cadastro');
/* Route::get('URL', [nome do Controller::class, 'nome da função do controller']) -> definir nome para identificar como rota name('usuarios.cadastro'); */
