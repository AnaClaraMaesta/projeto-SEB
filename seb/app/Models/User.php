<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class User
{
    private static $file = 'users.json';

    public static function create(array $dados): array
    {
        //carrega todos os usuarios
        // $users = self::all();
        // if(isset($dados['senha'])){
        //     $dados['senha'] = Hash::make($dados['senha']);
        // }

        $users[] = $dados;

        Storage::put(self::$file, json_encode($users, JSON_PRETTY_PRINT));

        return $dados;
    }

}




?>