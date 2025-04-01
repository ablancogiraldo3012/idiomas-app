<?php
declare(strict_types=1);

namespace IdiomasApp\Tests\Unit\Repositories;

use IdiomasApp\Core\Database;
use IdiomasApp\Models\ClassResource;
use IdiomasApp\Models\ExamResource;
use IdiomasApp\Repositories\ResourceRepository;
use InvalidArgumentException;
use PDO;
use PDOException;
use PDOStatement;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

/**
 * @covers \IdiomasApp\Repositories\ResourceRepository
 */
class ResourceRepositoryTest extends TestCase
{
    private ResourceRepository $repository;
    private PDO $pdo;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->pdo->method('setAttribute')->willReturn(true);

        $database = Database::getInstance([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'connection' => $this->pdo
        ]);

        $this->repository = new ResourceRepository($database, 3);
    }

    /**
     * @throws Exception
     */
    public function testFindsResourcesByPartialName(): void
    {
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->method('execute')->willReturn(true);
        $mockStatement->method('fetch')
            ->willReturnOnConsecutiveCalls(
                ['id' => 1, 'name' => 'English Class', 'type' => 'class', 'rating' => 5, 'exam_type' => null],
                ['id' => 2, 'name' => 'Spanish Exam', 'type' => 'exam', 'rating' => null, 'exam_type' => 'selección'],
                false
            );

        $this->pdo->method('prepare')->willReturn($mockStatement);

        $results = $this->repository->findByPartialName('search');

        $this->assertCount(2, $results);
        $this->assertInstanceOf(ClassResource::class, $results[0]);
        $this->assertInstanceOf(ExamResource::class, $results[1]);
    }

    public function testThrowsExceptionForShortSearchTerm(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('al menos 3 caracteres');
        $this->repository->findByPartialName('ab');
    }

    public function testThrowsExceptionForEmptySearchTerm(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('al menos 3 caracteres');
        $this->repository->findByPartialName('');
    }

    public function testHandlesDatabaseErrors(): void
    {
        $this->pdo->method('prepare')
            ->willThrowException(new PDOException('Database error'));

        $this->expectException(PDOException::class);
        $this->repository->findByPartialName('valid');
    }

    protected function tearDown(): void
    {
        Database::resetInstance();
    }
}