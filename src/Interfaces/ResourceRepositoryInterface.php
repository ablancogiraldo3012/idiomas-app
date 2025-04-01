<?php
declare(strict_types=1);

/**
 * Interfaz para repositorios de recursos educativos
 *
 * @package IdiomasApp\Interfaces
 * @version 1.0.0
 */
namespace IdiomasApp\Interfaces;

use IdiomasApp\Models\Resource;

interface ResourceRepositoryInterface
{
    /**
     * Busca recursos por coincidencia parcial del nombre
     *
     * @param string $searchTerm Término de búsqueda (mínimo 3 caracteres)
     * @return array<Resource> Colección de recursos que coinciden
     * @throws \InvalidArgumentException Si el término es demasiado corto
     * @throws \RuntimeException Si ocurre un error en la consulta
     */
    public function findByPartialName(string $searchTerm): array;
}