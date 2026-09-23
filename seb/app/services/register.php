<?php

namespace App\services;
use App\Models\User;

class register
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function register(User $user)
    {
        $filename = 'user.json';
        $user = json_encode($user);
        file_put_contents($filename, $user);

        return redirect()->route('login');
    }
}