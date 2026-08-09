<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/

Route::post('/getLogin', function(Request $request){
    try {
        $user = DB::table('users')
            ->where('email', $request->input('email'))
            ->where('senha', $request->input('senha'))
            ->first();

        if (! $user) {
            throw new Exception(alert('Cadastro não encontrado'));
        }

        return route('homePage');

    } catch (Exception $e) {
        return response()->json(['message' => 'Cadastro não encontrado'], 401);
    }

});