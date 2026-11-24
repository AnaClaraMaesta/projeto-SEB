<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        // Trava de segurança executada ANTES de o Laravel bootar e de o
        // RefreshDatabase rodar migrate:fresh. O phpunit.xml força SQLite em
        // memória; se algo sobrescrever isso (ex.: env_file do docker-compose),
        // abortamos sem tocar em nenhum banco.
        $connection = getenv('DB_CONNECTION');
        if ($connection === false) {
            $connection = $_ENV['DB_CONNECTION'] ?? $_SERVER['DB_CONNECTION'] ?? null;
        }

        $database = getenv('DB_DATABASE');
        if ($database === false) {
            $database = $_ENV['DB_DATABASE'] ?? $_SERVER['DB_DATABASE'] ?? null;
        }

        if ($connection !== 'sqlite' || $database !== ':memory:') {
            throw new RuntimeException(
                'Testes abortados: esperado SQLite em memória, recebido '
                .var_export($connection, true).' / '.var_export($database, true).'.'
            );
        }

        // O env_file do docker-compose popula $_SERVER com os valores do .env.
        // O repositório de env do Laravel lê $_SERVER antes de $_ENV, então o
        // force="true" do phpunit.xml (que só altera putenv/$_ENV) não basta.
        // Reforçamos os três superglobais antes de a aplicação bootar.
        foreach (['DB_CONNECTION' => 'sqlite', 'DB_DATABASE' => ':memory:', 'DB_URL' => ''] as $key => $value) {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }

        parent::setUp();

        // Confirma também a configuração já resolvida pela aplicação.
        if (config('database.default') !== 'sqlite'
            || config('database.connections.sqlite.database') !== ':memory:') {
            throw new RuntimeException(
                'Testes abortados: a configuração final não aponta para SQLite em memória.'
            );
        }
    }
}
