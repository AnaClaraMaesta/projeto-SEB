<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::redirect('/dashboard', function(){
    return view('homePage');
});

