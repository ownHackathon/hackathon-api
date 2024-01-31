<?php declare(strict_types=1);

namespace Test\Unit\App\Table;

use App\Table\ProjectTable;
use DateTime;
use Ramsey\Uuid\Rfc4122\UuidV7;
use Test\Unit\Mock\TestConstants;

/**
 * @property ProjectTable $table
 */
class ProjectTableTest extends AbstractTable
{
    private array $defaultProjectValue;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defaultProjectValue = [
            'id' => TestConstants::PROJECT_ID,
            'uuid' => UuidV7::fromString(TestConstants::PROJECT_UUID),
            'participantId' => TestConstants::PARTICIPANT_ID,
            'title' => TestConstants::PROJECT_TITLE,
            'description' => TestConstants::PROJECT_DESCRIPTION,
            'createdAt' => new DateTime(TestConstants::TIME),
            'gitRepoUri' => TestConstants::PROJECT_GIT_URL,
            'demoPageUri' => TestConstants::PROJECT_DEMO_URI,
        ];
    }

    public function testCanGetTableName(): void
    {
        self::assertSame('Project', $this->table->getTableName());
    }

    public function testCanFindById(): void
    {
        $project = $this->table->findById(TestConstants::PROJECT_ID);

        self::assertSame($this->fetchResult, $project);
    }

    public function testFindByIdHaveEmptyResult(): void
    {
        $project = $this->table->findById(TestConstants::PROJECT_ID_UNUSED);

        self::assertSame([], $project);
    }

    public function testCanFindAll(): void
    {
        $project = $this->table->findAll();

        self::assertSame($this->fetchAllResult, $project);
    }

    public function testCanFindByParticipantId(): void
    {
        $project = $this->table->findByParticipantId(TestConstants::PARTICIPANT_ID);

        self::assertSame($this->fetchResult, $project);
    }
}
