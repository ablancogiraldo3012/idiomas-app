<?php
declare(strict_types=1);

namespace IdiomasApp\Repositories;

use IdiomasApp\Core\Database;
use IdiomasApp\Interfaces\ResourceRepositoryInterface;
use IdiomasApp\Models\ClassResource;
use IdiomasApp\Models\ExamResource;
use InvalidArgumentException;
use PDO;
use PDOException;

/**
 * Repositorio para operaciones con recursos educativos
 *
 * Implementa el patrón Repository para separar la lógica de acceso a datos
 */
class ResourceRepository implements ResourceRepositoryInterface
{

    /**
     * @var PDO Conexión a la base de datos
     */
    private PDO $db;

    /**
     * @var int Longitud mínima requerida para el término
     */
    private int $minSearchLength;

    /**
     * Constructor
     *
     * @param Database $database Instancia de conexión a la base de datos
     * @param int $minSearchLength Longitud mínima para términos de búsqueda
     */
    public function __construct(Database $database, int $minSearchLength)
    {
        $this->db = $database->getConnection();
        $this->minSearchLength = $minSearchLength;
    }

    /**
     * {@inheritdoc}
     * @throws InvalidArgumentException Cuando el término es demasiado corto
     * @throws PDOException Si ocurre un error en la consulta SQL
     */
    public function findByPartialName(string $searchTerm): array
    {
        $this->validateSearchTerm($searchTerm);

        $query = $this->getSearchQuery();
        $stmt = $this->db->prepare($query);
        $stmt->execute(['term' => "%$searchTerm%"]);

        return $this->hydrateResources($stmt);
    }

    /**
     * Valida el término de búsqueda
     *
     * @param string $term Término a validar
     * @throws InvalidArgumentException Si el término es demasiado corto
     */
    private function validateSearchTerm(string $term): void
    {
        if (mb_strlen($term) < $this->minSearchLength) {
            throw new InvalidArgumentException(sprintf(
                'El término de búsqueda debe tener al menos %d caracteres',
                $this->minSearchLength
            ));
        }
    }

    /**
     * Construye la consulta SQL para búsqueda
     */
    private function getSearchQuery(): string
    {
        return "SELECT r.id, r.name, r.type, c.rating, e.exam_type
                FROM resources r
                LEFT JOIN classes c ON r.id = c.resource_id AND r.type = 'class'
                LEFT JOIN exams e ON r.id = e.resource_id AND r.type = 'exam'
                WHERE r.name LIKE :term
                ORDER BY r.type ASC, r.name";
    }

    /**
     * Convierte filas de BD en objetos de dominio
     *
     * @param \PDOStatement $stmt Resultados de la consulta
     * @return array<ClassResource|ExamResource>
     */
    private function hydrateResources(\PDOStatement $stmt): array
    {
        $resources = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $resources[] = $row['type'] === 'class'
                ? new ClassResource($row['id'], $row['name'], $row['rating'])
                : new ExamResource($row['id'], $row['name'], $row['exam_type']);
        }

        return $resources;
    }
}