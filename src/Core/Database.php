<?php
declare(strict_types=1);

namespace IdiomasApp\Core;

use PDO;
use PDOException;
use RuntimeException;
use InvalidArgumentException;

/**
 * Clase Singleton para manejar conexiones a bases de datos (MySQL y SQLite)
 *
 * @package IdiomasApp\Core
 * @version 1.0.0
 */
class Database
{
    private static ?self $instance = null;
    private PDO $connection;

    /**
     * Constructor privado para prevenir instanciación directa
     */
    private function __construct(array $config)
    {
        $this->validateConfig($config);
        $this->connection = $this->createConnection($config);
    }

    /**
     * Valida la configuración mínima requerida
     */
    private function validateConfig(array $config): void
    {
        if (isset($config['connection'])) {
            return;
        }

        $driver = $config['driver'] ?? 'mysql';

        if ($driver === 'sqlite') {
            if (!isset($config['database'])) {
                throw new InvalidArgumentException(
                    "Configuración SQLite incompleta. Falta: database"
                );
            }
        } else {
            $required = ['host', 'database', 'username', 'password'];
            foreach ($required as $key) {
                if (!isset($config[$key])) {
                    throw new InvalidArgumentException(
                        "Configuración MySQL incompleta. Falta: $key"
                    );
                }
            }
        }
    }

    /**
     * Crea la conexión PDO según el driver especificado
     */
    private function createConnection(array $config): PDO
    {
        if (isset($config['connection'])) {
            return $config['connection'];
        }

        $driver = $config['driver'] ?? 'mysql';
        $options = $config['options'] ?? [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        try {
            return $driver === 'sqlite'
                ? $this->createSQLiteConnection($config, $options)
                : $this->createMySQLConnection($config, $options);
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Error de conexión $driver: " . $e->getMessage()
            );
        }
    }

    private function createMySQLConnection(array $config, array $options): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['database'],
            $config['charset'] ?? 'utf8mb4'
        );

        return new PDO($dsn, $config['username'], $config['password'], $options);
    }

    private function createSQLiteConnection(array $config, array $options): PDO
    {
        $dsn = 'sqlite:' . (
            $config['database'] === ':memory:'
                ? ':memory:'
                : __DIR__ . '/../../' . $config['database']
            );

        return new PDO($dsn, null, null, $options);
    }

    public static function getInstance(array $config): self
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public static function resetInstance(): void
    {
        self::$instance = null;
    }
}