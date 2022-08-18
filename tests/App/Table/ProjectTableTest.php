<?php declare(strict_types=1);

namespace AppTest\Table;

use App\Table\ProjectTable;

/**
 * @property ProjectTable $table
 */
class ProjectTableTest extends AbstractTableTest
{
    public function testCanFindById(): void
    {
        $this->configureSelectWithOneWhere('id', 1);

        $project = $this->table->findById(1);

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
        $this->configureSelectWithOneWhere('participantId', 1);

        $project = $this->table->findByParticipantId(1);

        $this->assertSame($this->fetchResult, $project);
    }
}
