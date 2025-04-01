<?php
declare(strict_types=1);

namespace IdiomasApp\Models;

use InvalidArgumentException;

/**
 * Recurso de tipo examen de idiomas
 *
 * Representa un examen en el sistema con su tipo específico (selección, pregunta/respuesta, completación)
 * Extiende la clase base Resource e implementa su propia lógica de visualización
 */
class ExamResource extends Resource
{
    /**
     * @var string Tipo de examen: selección múltiple
     */
    public const TYPE_SELECTION = 'selección';

    /**
     * @var string Tipo de examen: pregunta y respuesta
     */
    public const TYPE_QA = 'pregunta y respuesta';

    /**
     * @var string Tipo de examen: completación
     */
    public const TYPE_COMPLETION = 'completación';

    /**
     * @var string Tipo de examen (selección|pregunta y respuesta|completación)
     */
    private string $examType;

    /**
     * Constructor del recurso examen
     *
     * @param int $id ID del examen
     * @param string $name Nombre del examen
     * @param string $examType Tipo de examen (selección|pregunta y respuesta|completación)
     */
    public function __construct(int $id, string $name, string $examType)
    {
        parent::__construct($id, $name, parent::TYPE_EXAM);

        if (!in_array($examType, $this->getValidTypes())) {
            $validTypes = implode(', ', [
                self::TYPE_SELECTION,
                self::TYPE_QA,
                self::TYPE_COMPLETION
            ]);
            $message = sprintf(
                'Tipo de examen inválido. Use: %s',
                $validTypes
            );
            throw new InvalidArgumentException($message);
        }

        $this->examType = $examType;
    }

    /**
     * Obtiene los tipos de examen válidos
     *
     * @return array<string> Array con las constantes de tipos válidos
     */
    public function getValidTypes(): array
    {
        return [
            self::TYPE_SELECTION,
            self::TYPE_QA,
            self::TYPE_COMPLETION
        ];
    }

    /**
     * Obtiene el tipo específico de examen
     *
     * @return string Uno de: selección, pregunta y respuesta, completación
     */
    public function getExamType(): string
    {
        return $this->examType;
    }

    /**
     * Representación en string del examen
     *
     * @return string Formato: "Examen: [nombre] | [tipo]"
     */
    public function display(): string
    {
        return sprintf("Examen: %s | Tipo: %s", $this->name, $this->examType);
    }

    /**
     * Busca coincidencias del término en el nombre del examen
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