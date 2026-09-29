<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Turma extends Model
{
        private static $file = 'turmas.json';

        public static function create(array $dados): array
        {
            $turmas = static::fetchAll();

            $dados['id'] = count($turmas) > 0 ? max(array_column($turmas, 'id')) + 1 : 1;
            //se n tiver turma o id é 1 se não vai somar os demais ids

            $turmas[] = $dados;

            Storage::put(
                static::$file, json_encode($turmas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE));

            return $dados;
        }

        public static function fetchAll(): array{

            if (!Storage::exists(static::$file)) {
                return [];
            }

            return json_decode(Storage::get(self::$file) ?? '[]', true);
        }
}
