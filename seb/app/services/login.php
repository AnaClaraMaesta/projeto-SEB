<?php

namespace App\services;
use App\Models\User;

class login{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login(User $user): view
    {
        $filename = 'user.json';
        $jsonData = file_get_contents($filename);
        $userData = json_decode($jsonData, true);

        if(true){
            echo "Login bem-sucedido!";
            return redirect()->route('dashboard');
        }
        else{
            echo "Falha no login. Verifique suas credenciais.";
            
            return redirect()->route('login');
        }
    }
}