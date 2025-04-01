<?php
declare(strict_types=1);

namespace IdiomasApp\Tests\Unit\Models;

use IdiomasApp\Models\ExamResource;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \IdiomasApp\Models\ExamResource
 */
class ExamResourceTest extends TestCase
{
    private const VALID_ID = 1;
    private const VALID_NAME = 'English Final Exam';
    private const VALID_TYPE = 'selección';

    public function testExamResourceInitialization(): void
    {
        $resource = new ExamResource(
            self::VALID_ID,
            self::VALID_NAME,
            self::VALID_TYPE
        );

        $this->assertSame(self::VALID_ID, $resource->getId());
        $this->assertSame(self::VALID_NAME, $resource->getName());
        $this->assertSame(self::VALID_TYPE, $resource->getExamType());
        $this->assertSame('exam', $resource->getType());
    }

    public function testDisplayFormat(): void
    {
        $resource = new ExamResource(2, 'Spanish Midterm', 'pregunta y respuesta');

        $expected = "Examen: Spanish Midterm | Tipo: pregunta y respuesta";
        $this->assertSame($expected, $resource->display());
    }

    public function testThrowsExceptionForInvalidExamType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tipo de examen inválido.');

        new ExamResource(3, 'Invalid Type', 'invalid_type');
    }

    public function testAllValidExamTypes(): void
    {
        $validTypes = [
            ExamResource::TYPE_SELECTION,
            ExamResource::TYPE_QA,
            ExamResource::TYPE_COMPLETION
        ];

        foreach ($validTypes as $index => $type) {
            $resource = new ExamResource($index + 1, "Exam $type", $type);
            $this->assertSame($type, $resource->getExamType());
        }
    }
}