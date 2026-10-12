<?php

declare(strict_types=1);

namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Throwable;

final class LoggerFactory
{
    public static function create(): ?LoggerInterface
    {
        try {
            $directory = dirname(__DIR__) . '/storage/logs';

            if (
                !is_dir($directory)
                && !@mkdir($directory, 0775, true)
                && !is_dir($directory)
            ) {
                return null;
            }

            $logger = new Logger('app');

            $logger->pushHandler(
                new StreamHandler(
                    $directory . '/app.log',
                    Logger::INFO
                )
            );

            return $logger;
        } catch (Throwable) {
            // Проблемы с логированием не должны ломать приложение.
            return null;
        }
    }
}
