<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class login{

    private static $file = 'users.json';

    public static function validar(array $dados) : bool{
        $users = self::fetchAll();

        $found = collect($users)->firstWhere('email', $dados['email']);

        if(!$found) return false;
        
        return $dados['senha'] === $found['senha'];
    }

    public static function fetchAll(){

        if(!Storage::exists(self::$file)){
            return [];
        }

        $contents = Storage::get(self::$file);
        return json_decode($contents, true) ?? [];
    }
}