<?php declare(strict_types=1);

namespace App\Test\Table;

use App\Table\ProjectTable;
use App\Test\Mock\TestConstants;

/**
 * @property ProjectTable $table
 */
class ProjectTableTest extends AbstractTableTest
{
    public function testCanGetTableName(): void
    {
        $this->assertSame('Project', $this->table->getTableName());
    }

    public function testCanFindById(): void
    {
        $project = $this->table->findById(TestConstants::PROJECT_ID);

        $this->assertSame($this->fetchResult, $project);
    }

    public function testCanFindAll(): void
    {
        $project = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $project);
    }

    public function testCanFindByParticipantId(): void
    {
        $project = $this->table->findByParticipantId(TestConstants::PARTICIPANT_ID);

        $this->assertSame($this->fetchResult, $project);
    }
}
