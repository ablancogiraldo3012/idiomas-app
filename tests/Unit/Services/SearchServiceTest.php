<?php
declare(strict_types=1);

namespace IdiomasApp\Tests\Unit\Services;

use IdiomasApp\Models\ClassResource;
use IdiomasApp\Models\ExamResource;
use IdiomasApp\Repositories\ResourceRepository;
use IdiomasApp\Services\SearchService;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

/**
 * @covers \IdiomasApp\Services\SearchService
 */
class SearchServiceTest extends TestCase
{
    private SearchService $service;
    private ResourceRepository $repositoryMock;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(ResourceRepository::class);
        $this->service = new SearchService($this->repositoryMock);
    }

    public function testFormatsClassAndExamResourcesCorrectly(): void
    {
        $mockResources = [
            new ClassResource(1, 'Advanced English', 5),
            new ExamResource(2, 'English Final Test', 'selección')
        ];

        $this->configureRepositoryMock('eng', $mockResources);

        $results = $this->service->searchAndDisplay('eng');

        $this->assertSame([
            'Clase: Advanced English | 5/5',
            'Examen: English Final Test | Tipo: selección'
        ], $results);
    }

    public function testReturnsEmptyArrayWhenNoResultsFound(): void
    {
        $this->configureRepositoryMock('nonexistent', []);

        $results = $this->service->searchAndDisplay('nonexistent');

        $this->assertEmpty($results);
    }

    public function testPropagatesRepositoryExceptions(): void
    {
        $this->configureRepositoryMock('ab', new InvalidArgumentException('Invalid term'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid term');

        $this->service->searchAndDisplay('ab');
    }

    public function testHandlesMixedResourceTypes(): void
    {
        $mockResources = [
            new ExamResource(1, 'Spanish Oral Exam', 'pregunta y respuesta'),
            new ClassResource(2, 'Spanish Grammar', 4)
        ];

        $this->configureRepositoryMock('spanish', $mockResources);

        $results = $this->service->searchAndDisplay('spanish');

        $this->assertSame([
            'Examen: Spanish Oral Exam | Tipo: pregunta y respuesta',
            'Clase: Spanish Grammar | 4/5'
        ], $results);
    }

    private function configureRepositoryMock(string $expectedTerm, $willReturn): void
    {
        $method = $this->repositoryMock->expects($this->once())
            ->method('findByPartialName')
            ->with($this->equalTo($expectedTerm));

        if ($willReturn instanceof \Exception) {
            $method->willThrowException($willReturn);
        } else {
            $method->willReturn($willReturn);
        }
    }
}