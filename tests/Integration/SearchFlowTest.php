<?php
declare(strict_types=1);

namespace IdiomasApp\Tests\Integration;

use IdiomasApp\Core\Database;
use IdiomasApp\Repositories\ResourceRepository;
use IdiomasApp\Services\SearchService;
use PDO;
use PHPUnit\Framework\TestCase;

class SearchFlowTest extends TestCase
{
    private PDO $pdo;
    private Database $database;
    private ResourceRepository $repository;
    private SearchService $service;

    protected function setUp(): void
    {
        $this->initializeTestDatabase();
        $this->service = new SearchService(
            new ResourceRepository($this->database, 3)
        );
    }

    private function initializeTestDatabase(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->createSchema();
        $this->seedTestData();

        $this->database = Database::getInstance([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'connection' => $this->pdo
        ]);
    }

    private function createSchema(): void
    {
        $this->pdo->exec("
            CREATE TABLE resources (
                id INTEGER PRIMARY KEY,
                name TEXT NOT NULL,
                type TEXT NOT NULL CHECK(type IN ('class', 'exam')),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
            
            CREATE TABLE classes (
                resource_id INTEGER PRIMARY KEY,
                rating INTEGER NOT NULL CHECK (rating BETWEEN 1 AND 5),
                FOREIGN KEY (resource_id) REFERENCES resources(id) ON DELETE CASCADE
            );
            
            CREATE TABLE exams (
                resource_id INTEGER PRIMARY KEY,
                exam_type TEXT NOT NULL CHECK(exam_type IN (
                    'selección', 
                    'pregunta y respuesta', 
                    'completación'
                )),
                FOREIGN KEY (resource_id) REFERENCES resources(id) ON DELETE CASCADE
            );
        ");
    }

    private function seedTestData(): void
    {
        $this->pdo->exec("
            BEGIN TRANSACTION;
            INSERT INTO resources (name, type) VALUES 
                ('English Advanced Class', 'class'),
                ('Spanish Basic Exam', 'exam'),
                ('French Conversation Class', 'class');
            
            INSERT INTO classes (resource_id, rating) VALUES 
                (1, 5),
                (3, 4);
            
            INSERT INTO exams (resource_id, exam_type) VALUES 
                (2, 'selección');
            COMMIT;
        ");
    }

    public function testSearchReturnsClassResources(): void
    {
        $results = $this->service->searchAndDisplay('eng');

        $this->assertCount(1, $results);
        $this->assertEquals(
            'Clase: English Advanced Class | 5/5',
            $results[0]
        );
    }

    public function testSearchReturnsExamResources(): void
    {
        $results = $this->service->searchAndDisplay('span');

        $this->assertCount(1, $results);
        $this->assertEquals(
            'Examen: Spanish Basic Exam | Tipo: selección',
            $results[0]
        );
    }

    public function testSearchWithMultipleMatches(): void
    {
        $results = $this->service->searchAndDisplay('class');

        $this->assertCount(2, $results);
        $this->assertStringContainsString('English Advanced Class', $results[0]);
        $this->assertStringContainsString('French Conversation Class', $results[1]);
    }

    protected function tearDown(): void
    {
        Database::resetInstance();
        unset($this->pdo);
    }
}