<?php declare(strict_types=1);

namespace Test\Unit\App\Table;

use App\Table\ProjectTable;
use Test\Unit\Mock\TestConstants;

/**
 * @property ProjectTable $table
 */
class ProjectTableTest extends AbstractTable
{
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
