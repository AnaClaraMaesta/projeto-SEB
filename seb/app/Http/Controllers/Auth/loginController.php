<?php

namespace App\Http\Controllers\Auth;
use App\Http\middleware\loginRequest;
use App\serivces\login;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class loginController
{   
    public function index(Request $request)
    {
        return view('layouts.login');
    }

    public function create(Request $request)
    {
        return view('usuario.create');
    }

    public function store(loginRequest $request) : RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended('dashboard');
    }

}
