<?php

namespace App\Http\Controllers;
use App\Http\Requests\loginRequest;
use Illuminate\Http\Request;

class loginController extends Controller
{

    public function create()
    {
        return view('layouts.login');
    }

    public function store(loginRequest $request) : RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended('dashboard');
    }

}
