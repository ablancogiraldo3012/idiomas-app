<?php
declare(strict_types=1);

namespace IdiomasApp\Models;

use IdiomasApp\Interfaces\Searchable;
use InvalidArgumentException;

/**
 * Clase abstracta base para todos los recursos educativos
 *
 * Define la estructura común para Clases y Exámenes, implementando la interfaz Searchable.
 * Esta clase no debe ser instanciada directamente, sino extendida por clases concretas.
 */
abstract class Resource implements Searchable
{
    /**
     * @var string Tipo de recurso para clases de idiomas
     */
    public const TYPE_CLASS = 'class';

    /**
     * @var string Tipo de recurso para exámenes de idiomas
     */
    public const TYPE_EXAM = 'exam';

    /**
     * @var int Identificador único del recurso
     */
    protected int $id;

    /**
     * @var string Nombre descriptivo del recurso
     */
    protected string $name;

    /**
     * @var string Tipo de recurso ('class' o 'exam')
     */
    protected string $type;

    /**
     * Constructor base para todos los recursos
     *
     * @param int $id Identificador único
     * @param string $name Nombre del recurso
     * @param string $type Tipo de recurso (debe ser TYPE_CLASS o TYPE_EXAM)
     * @throws InvalidArgumentException Si el tipo de recurso no es válido
     */

    public function __construct(int $id, string $name, string $type)
    {
        if (!in_array($type, [self::TYPE_CLASS, self::TYPE_EXAM])) {
            $message = sprintf(
                'Tipo de recurso inválido. Use %s::TYPE_CLASS o %s::TYPE_EXAM',
                __CLASS__,
                __CLASS__
            );
            throw new InvalidArgumentException($message);
        }

        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * Obtiene el ID del recurso
     *
     * @return int Identificador único
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Obtiene el nombre del recurso
     *
     * @return string Nombre descriptivo
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Obtiene el tipo de recurso
     *
     * @return string 'class' o 'exam'
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Método abstracto para mostrar el recurso
     *
     * Cada clase concreta debe implementar su propia representación
     * @return string Representación formateada del recurso
     */
    abstract public function display(): string;
}