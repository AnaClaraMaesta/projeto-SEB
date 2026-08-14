<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
Route::get('/', function () {
    return view('layouts.login');
});

Route::get('/dashboard', function(){
    return view('layouts.homePage');
});



Route::get('/criar-usuario', [userController::class, 'create']) -> name('usuarios.create');
/* Route::get('URL', [nome do Controller::class, 'nome da função do controller']) -> definir nome para identificar como rota name('usuarios.cadastro'); */
