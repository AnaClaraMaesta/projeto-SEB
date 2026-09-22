<?php

namespace App\Http\Controllers\Auth;
use App\Http\Requests\loginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class loginController
{   
    public function index()
    {
        return view('layouts.login');
    }

    public function create()
    {
        return view('layouts.usuarios.createUser');
    }

    public function store(loginRequest $request) : RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended('dashboard');
    }

}
