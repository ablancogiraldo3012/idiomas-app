<?php
declare(strict_types=1);

namespace IdiomasApp\Models;

use InvalidArgumentException;

/**
 * Recurso de tipo clase de idioma
 *
 * Representa una clase del sistema con su ponderación de calidad
 */
class ClassResource extends Resource
{
    /**
     * @var int Valor mínimo permitido para rating (inclusive)
     */
    public const MIN_RATING = 1;

    /**
     * @var int Valor máximo permitido para rating (inclusive)
     */
    public const MAX_RATING = 5;

    /**
     * @var int Ponderación de la clase (1-5)
     */
    private int $rating;

    /**
     * Constructor
     *
     * @param int $id id del recurso
     * @param string $name Nombre de la clase
     * @param int $rating Ponderación (1-5)
     * @throws InvalidArgumentException Si el rating está fuera del rango permitido
     */
    public function __construct(int $id, string $name, int $rating)
    {
        parent::__construct($id, $name, parent::TYPE_CLASS);

        if ($rating < self::MIN_RATING || $rating > self::MAX_RATING) {
            throw new InvalidArgumentException(
                sprintf('La clasificación debe estar entre %d y %d', self::MIN_RATING, self::MAX_RATING)
            );
        }

        $this->rating = $rating;
    }

    /**
     * Obtiene la ponderación de la clase
     *
     * @return int Valor de 1 a 5
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * Representación en string de la clase
     *
     * @return string Formato: "Clase: [nombre] | [rating]/5"
     */
    public function display(): string
    {
        return sprintf("Clase: %s | %d/%d", $this->name, $this->rating, self::MAX_RATING);
    }

    /**
     * Busca coincidencias del término en el nombre de la clase
     *
     * @param string $term Término de búsqueda (no sensible a mayúsculas/minúsculas)
     * @return array Array conteniendo esta instancia si hay coincidencia, o array vacío si no
     */
    public function search(string $term): array
    {
        // TODO: Implement search() method.
        return stripos($this->name, $term) !== false ? [$this] : [];
    }
}