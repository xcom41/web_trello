<?php
declare(strict_types=1);

namespace App;

use RuntimeException;

final class Config
{
    private static ?array $config = null;

    private static function load(): void
    {
        if (self::$config !== null) {
            return;
        }

        $file = dirname(__DIR__) . '/config/app.php';

        if (!is_file($file)) {
            throw new RuntimeException('Config file not found: ' . $file);
        }

        self::$config = require $file;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::load();

        $value = self::$config;

        foreach (explode('.', $key) as $part) {
            if (!is_array($value) || !array_key_exists($part, $value)) {
                return $default;
            }

            $value = $value[$part];
        }

        if (is_string($value) && str_starts_with($value, 'enc:')) {
            return self::decrypt(substr($value, 4));
        }

        return $value;
    }

    public static function encrypt(string $value): string
    {
        $key = self::getKey();
        $iv = random_bytes(12);
        $tag = '';

        $ciphertext = openssl_encrypt(
            $value,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        if ($ciphertext === false) {
            throw new RuntimeException('Encryption failed');
        }

        return 'enc:' . base64_encode($iv . $tag . $ciphertext);
    }

    private static function decrypt(string $value): string
    {
        $data = base64_decode($value, true);

        if ($data === false || strlen($data) < 28) {
            throw new RuntimeException('Invalid encrypted config value');
        }

        $plaintext = openssl_decrypt(
            substr($data, 28),
            'aes-256-gcm',
            self::getKey(),
            OPENSSL_RAW_DATA,
            substr($data, 0, 12),
            substr($data, 12, 16)
        );

        if ($plaintext === false) {
            throw new RuntimeException('Config decryption failed');
        }

        return $plaintext;
    }

    private static function getKey(): string
    {
        $encoded = getenv('APP_CONFIG_KEY');

        if ($encoded === false || $encoded === '') {
            throw new RuntimeException('APP_CONFIG_KEY is not configured');
        }

        $key = base64_decode($encoded, true);

        if ($key === false || strlen($key) !== 32) {
            throw new RuntimeException(
                'APP_CONFIG_KEY must be Base64-encoded 32-byte key'
            );
        }

        return $key;
    }
}
