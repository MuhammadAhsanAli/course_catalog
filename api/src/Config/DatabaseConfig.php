<?php

namespace App\Config;

class DatabaseConfig
{
    /**
     * Get the database configuration.
     *
     * @return array Database connection parameters
     */
    public static function getDatabaseConfig(): array
    {
        return [
            'dsn' => 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DATABASE'],
            'username' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
        ];
    }
}
