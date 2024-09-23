<?php

namespace App;

use DI\ContainerBuilder;
use Exception;
use PDO;
use App\Config\DatabaseConfig;

class Container
{
    /**
     * Create and build the DI container.
     *
     * @return \DI\Container The built DI container.
     * @throws Exception
     */
    public static function create(): \DI\Container
    {
        $containerBuilder = new ContainerBuilder();
        $config = DatabaseConfig::getDatabaseConfig();

        $containerBuilder->addDefinitions([
            PDO::class => function () use ($config) {
                return new PDO($config['dsn'], $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            }
        ]);

        return $containerBuilder->build();
    }
}
