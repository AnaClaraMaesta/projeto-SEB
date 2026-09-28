<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Turma extends Model
{
        private static $file = 'turmas.json';

        public function create(array $dados): array
        {
            $turmas[] = $dados;

            Storage::put(self::$file, json_encode($turmas, JSON_PRETTY_PRINT));

            return $dados;
        }

        public function fetchAll(){
            if(!Storage::exists(self::$file)){
                
            }

        }
}
