<?php
declare(strict_types=1);

/**
 * Interface para recursos buscables y representables
 *
 * @package IdiomasApp\Interfaces
 * @version 1.0.0
 */
namespace IdiomasApp\Interfaces;

interface Searchable
{
    /**
     * Busca recursos que coincidan con el término
     *
     * @param string $term Término de búsqueda (mínimo 3 caracteres)
     * @return array Resultados de búsqueda
     * @throws \InvalidArgumentException Si el término no cumple requisitos
     */
    public function search(string $term): array;

    /**
     * Representación formateada para visualización
     *
     * @return string Representación lista para mostrar
     */
    public function display(): string;
}