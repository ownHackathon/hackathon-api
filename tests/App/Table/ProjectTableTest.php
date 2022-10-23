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
        $this->configureSelectWithOneWhere('id', self::TEST_PROJECT_ID);

        $project = $this->table->findById(self::TEST_PROJECT_ID);

        $this->assertSame($this->fetchResult, $project);
    }

    public function testCanFindAll(): void
    {
        $select = $this->createSelect();

        $select->expects($this->once())
            ->method('fetchAll')
            ->willReturn($this->fetchAllResult);

        $project = $this->table->findAll();

        $this->assertSame($this->fetchAllResult, $project);
    }

    public function testCanFindByParticipantId(): void
    {
        $this->configureSelectWithOneWhere('participantId', self::TEST_PARTICIPANT_ID);

        $project = $this->table->findByParticipantId(self::TEST_PARTICIPANT_ID);

        $this->assertSame($this->fetchResult, $project);
    }
}
