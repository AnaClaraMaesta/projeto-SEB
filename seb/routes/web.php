<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.login');
});

Route::redirect('/dashboard', function(){
    return view('layouts.homePage');
});

Route::redirect('/cadastrarUser', function(){
    return view('layouts.cadastrarUser');
});