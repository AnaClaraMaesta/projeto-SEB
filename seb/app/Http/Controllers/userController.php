<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class userController extends Controller
{

    public function index(Request $request)
    {
        return view('layouts.login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('usuario.createUser');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    // public function update(Request $request, string $id)
   // {
       // $user = User::findOrFail(($id));

       // $data = $request->validate([
     //       ''=>''
   //     ])
 //   }
//

    public function destroy(string $id)
    {
        //
    }
}