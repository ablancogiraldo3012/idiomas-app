<?php
declare(strict_types=1);

/**
 * Configuración global de la aplicación
 *
 * @var array{
 *     database: array{
 *         host: string,
 *         port: int,
 *         database: string,
 *         username: string,
 *         password: string,
 *         charset: string,
 *         collation: string,
 *         options: array
 *     },
 *     search: array{
 *         min_length: int
 *     },
 *     commands: array{
 *         help: string[]
 *     }
 * }
 */
return [
    'database' => [
        'host' => 'localhost',
        'port' => 3306,
        'database' => 'idiomas_db',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    ],
    'search' => [
        'min_length' => 3
    ]
];