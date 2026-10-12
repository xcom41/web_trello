<?php

declare(strict_types=1);

use App\Migration\Migrator;

require dirname(__DIR__) . '/vendor/autoload.php';

try {
    $executed = Migrator::run();

    if ($executed === []) {
        echo "Новых миграций нет.\n";
    } else {
        foreach ($executed as $name) {
            echo "Выполнена миграция: {$name}\n";
        }
    }
} catch (Throwable $e) {
    fwrite(STDERR, "Ошибка миграции: {$e->getMessage()}\n");
    exit(1);
}
