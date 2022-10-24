<?php declare(strict_types=1);

namespace App\Test\Table;

use App\Table\ProjectTable;

/**
 * @property ProjectTable $table
 */
class ProjectTableTest extends AbstractTableTest
{
    private const TEST_PROJECT_ID = 1;
    private const TEST_PARTICIPANT_ID = 1;

    public function testCanFindById(): void
    {
        $project = $this->table->findById(self::TEST_PROJECT_ID);

        $this->assertSame($this->fetchResult, $project);
    }

    public function testCanFindAll(): void
    {
        $project = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $project);
    }

    public function testCanFindByParticipantId(): void
    {
        $project = $this->table->findByParticipantId(self::TEST_PARTICIPANT_ID);

        $this->assertSame($this->fetchResult, $project);
    }
}
