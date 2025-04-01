#!/usr/bin/env php
<?php
declare(strict_types=1);

/**
 * Punto de entrada principal para la aplicación de búsqueda de recursos de idiomas
 *
 * @package IdiomasApp
 * @version 1.0.0
 *
 * @example
 *   php main.php search trabajo
 *
 * @exitcode 0 Ejecución exitosa
 * @exitcode 1 Error en argumentos
 * @exitcode 2 Error de base de datos
 * @exitcode 3 Error inesperado
 */

require __DIR__ . '/../vendor/autoload.php';

use IdiomasApp\Core\Database;
use IdiomasApp\Repositories\ResourceRepository;
use IdiomasApp\Services\SearchService;

// ==============================================
// Carga manual de variables de entorno
// ==============================================
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $env = parse_ini_file($envFile);
    if ($env !== false) {
        foreach ($env as $key => $value) {
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

// Configuración de base de datos desde .env
$dbConfig = [
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'database' => $_ENV['DB_NAME'] ?? 'idiomas_db',
    'username' => $_ENV['DB_USER'] ?? 'root',
    'password' => $_ENV['DB_PASS'] ?? '',
    'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
];

// Configuración de búsqueda
$searchMinLength = (int)($_ENV['SEARCH_MIN_LENGTH'] ?? 3);

// ==============================================
// Manejo de comandos
// ==============================================
if ($argc === 1 || in_array($argv[1], ['--help', '-h', 'help'])) {
    echo <<<HELP
    BÚSQUEDA DE RECURSOS DE IDIOMAS
    
    USO:
      php main.php search <término>
    
    REQUISITOS:
      - Término mínimo: $searchMinLength caracteres
    
    OPCIONES:
      --help, -h, help  Muestra esta ayuda
    
    CÓDIGOS DE SALIDA:
      0 - Éxito              1 - Error en argumentos
      2 - Error de base datos 3 - Error inesperado
    
    EJEMPLOS:
      php main.php search trabajo
      php main.php search --help
    HELP;
    exit(0);
}

// Validación de argumentos
if ($argc < 3 || $argv[1] !== 'search') {
    fwrite(STDERR, "ERROR: Uso incorrecto\n");
    fwrite(STDERR, "Formato correcto: php main.php search <término>\n");
    exit(1);
}

$term = trim($argv[2]);

// Validación de término
if (mb_strlen($term) < $searchMinLength) {
    fwrite(STDERR, sprintf(
        "ERROR: El término debe contener al menos %d caracteres\n",
        $searchMinLength
    ));
    exit(1);
}

// ==============================================
// Ejecución principal
// ==============================================
try {
    // Inicialización con la configuración original
    $database = Database::getInstance($dbConfig);
    $repository = new ResourceRepository($database, $searchMinLength);
    $service = new SearchService($repository);

    // Búsqueda
    $results = $service->searchAndDisplay($term);

    // Salida
    if (empty($results)) {
        echo "No se encontraron resultados para '$term'\n";
        exit(0);
    }

    echo implode("\n", $results) . "\n";
    exit(0);
} catch (PDOException $e) {
    fwrite(STDERR, "ERROR DB: " . $e->getMessage() . "\n");
    exit(2);
} catch (InvalidArgumentException $e) {
    fwrite(STDERR, "ERROR: " . $e->getMessage() . "\n");
    exit(1);
} catch (Exception $e) {
    fwrite(STDERR, "ERROR INESPERADO: " . $e->getMessage() . "\n");
    exit(3);
}