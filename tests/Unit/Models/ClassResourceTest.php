<?php
declare(strict_types=1);

namespace IdiomasApp\Tests\Unit\Models;

use IdiomasApp\Models\ClassResource;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \IdiomasApp\Models\ClassResource
 */
class ClassResourceTest extends TestCase
{
    private const VALID_ID = 1;
    private const VALID_NAME = 'English Advanced';
    private const VALID_RATING = 5;

    public function testClassResourceInitialization(): void
    {
        $resource = new ClassResource(
            self::VALID_ID,
            self::VALID_NAME,
            self::VALID_RATING
        );

        $this->assertSame(self::VALID_ID, $resource->getId());
        $this->assertSame(self::VALID_NAME, $resource->getName());
        $this->assertSame(self::VALID_RATING, $resource->getRating());
        $this->assertSame('class', $resource->getType());
    }

    public function testDisplayFormat(): void
    {
        $resource = new ClassResource(2, 'Spanish Basics', 4);

        $expected = "Clase: Spanish Basics | 4/5";
        $this->assertSame($expected, $resource->display());
    }

    public function testInvalidRatingThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('La clasificación debe estar entre 1 y 5');

        new ClassResource(3, 'Invalid Rating', 6);
    }

    public function testMinimumRating(): void
    {
        $resource = new ClassResource(4, 'Minimum Rating', 1);
        $this->assertSame(1, $resource->getRating());
    }

    public function testMaximumRating(): void
    {
        $resource = new ClassResource(5, 'Maximum Rating', 5);
        $this->assertSame(5, $resource->getRating());
    }
}