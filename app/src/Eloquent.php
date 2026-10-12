<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use PDO;

final class Eloquent
{
    private static ?Capsule $capsule = null;

    public static function boot(): Capsule
    {
        if (self::$capsule !== null) {
            return self::$capsule;
        }

        $capsule = new Capsule();

        $capsule->addConnection([
            'driver' => 'pgsql',
            'host' => getenv('DB_HOST') ?: 'postgres',
            'port' => getenv('DB_PORT') ?: '5432',
            'database' => getenv('DB_NAME') ?: 'app',
            'username' => getenv('DB_USER') ?: 'app',
            'password' => getenv('DB_PASSWORD') ?: '',
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3,
            ],
        ]);

        $capsule->setEventDispatcher(
            new Dispatcher(new Container())
        );

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        self::$capsule = $capsule;

        return self::$capsule;
    }
}
