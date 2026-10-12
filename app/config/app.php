<?php
declare(strict_types=1);

return [
    'app' => [
        'name' => 'Local Zoo',
        'environment' => getenv('APP_ENV') ?: 'local',
        'debug' => filter_var(
            getenv('APP_DEBUG') ?: 'false',
            FILTER_VALIDATE_BOOLEAN
        ),
    ],

    'services' => [
        // Замените пример на значение,
        // полученное через encrypt-config.php.
        'external_api_key' => 'enc:REPLACE_WITH_ENCRYPTED_VALUE',
    ],
];
