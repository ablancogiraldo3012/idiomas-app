<?php
require __DIR__ . '/../vendor/autoload.php';

// Configuración básica para testing
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Establecer variables de entorno directamente
putenv('APP_ENV=testing');
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');
putenv('SEARCH_MIN_LENGTH=3');

// Configuración manual de la base de datos para pruebas
if (getenv('DB_CONNECTION') === 'sqlite') {
    $dbPath = __DIR__ . '/../database/testing.sqlite';
    if (!file_exists($dbPath)) {
        touch($dbPath); // Crear archivo SQLite si no existe
    }
    putenv("DB_DATABASE=$dbPath");
}

// Función helper para pruebas (opcional)
if (!function_exists('test_path')) {
    function test_path($path = '') {
        return __DIR__ . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}