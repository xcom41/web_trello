<?php

declare(strict_types=1);

namespace App\Migration;

use App\Eloquent;
use Illuminate\Database\Schema\Blueprint;
use RuntimeException;

final class Migrator
{
    /**
     * Выполняет ещё не запущенные миграции.
     *
     * Возвращает список выполненных миграций.
     */
    public static function run(): array
    {
        $capsule = Eloquent::boot();
        $schema = $capsule->schema();
        $connection = $capsule->getConnection();

        if (!$schema->hasTable('migrations')) {
            $schema->create('migrations', function (Blueprint $table): void {
                $table->bigIncrements('id');
                $table->string('migration')->unique();
                $table->unsignedInteger('batch');
                $table->timestamp('ran_at')->useCurrent();
            });
        }

        $directory = dirname(__DIR__, 2) . '/migrations';
        $files = glob($directory . '/*.php') ?: [];
        sort($files);

        $batch = ((int) $connection
            ->table('migrations')
            ->max('batch')) + 1;

        $executed = [];

        foreach ($files as $file) {
            $name = basename($file, '.php');

            $alreadyRan = $connection
                ->table('migrations')
                ->where('migration', $name)
                ->exists();

            if ($alreadyRan) {
                continue;
            }

            $migration = require $file;

            if (!is_object($migration) || !method_exists($migration, 'up')) {
                throw new RuntimeException(
                    "Некорректная миграция: {$name}"
                );
            }

            // Сначала выполняем изменение схемы.
            $migration->up();

            // Фиксируем успешное выполнение.
            $connection->table('migrations')->insert([
                'migration' => $name,
                'batch' => $batch,
            ]);

            $executed[] = $name;
        }

        return $executed;
    }
}
