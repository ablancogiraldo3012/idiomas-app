<?php
declare(strict_types=1);

namespace IdiomasApp\Services;

use IdiomasApp\Interfaces\Searchable;
use IdiomasApp\Repositories\ResourceRepository;

/**
 * Servicio para búsqueda y visualización de recursos educativos
 *
 * @package IdiomasApp\Services
 * @version 1.0.0
 */
class SearchService
{
    /**
     * @var ResourceRepository Repositorio de recursos
     */
    private ResourceRepository $repository;

    /**
     * Constructor
     *
     * @param ResourceRepository $repository Repositorio inyectado
     */
    public function __construct(ResourceRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Busca recursos y devuelve sus representaciones formateadas
     *
     * @param string $term Término de búsqueda (mínimo 3 caracteres)
     * @return array<string> Resultados formateados para visualización
     * @throws \InvalidArgumentException Si el término es demasiado corto
     * @throws \PDOException Si ocurre un error al acceder a la base de datos
     */
    public function searchAndDisplay(string $term, ?callable $formatter = null): array
    {
        $resources = $this->repository->findByPartialName($term);
        $formatter = $formatter ?? fn(Searchable $r) => $r->display();

        return array_map($formatter, $resources);
    }
}