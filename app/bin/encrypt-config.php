<?php
declare(strict_types=1);

use App\Config;

require dirname(__DIR__) . '/vendor/autoload.php';

if (!function_exists('openssl_encrypt')) {
    fwrite(STDERR, "OpenSSL extension is required.\n");
    exit(1);
}

fwrite(STDERR, "Введите значение для шифрования: ");
$value = trim((string) fgets(STDIN));

if ($value === '') {
    fwrite(STDERR, "Пустое значение не допускается.\n");
    exit(1);
}

try {
    echo Config::encrypt($value) . PHP_EOL;
} catch (Throwable $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
}
