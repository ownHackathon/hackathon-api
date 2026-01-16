<?php declare(strict_types=1);

namespace Test\Unit\App\Table;

use App\Table\ProjectTable;
use Test\Data\Entity\ProjectTestEntity;
use Test\Data\TestConstants;
use Test\Unit\Core\Table\AbstractTable;

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

        self::assertEquals(ProjectTestEntity::getDefaultProjectValue(), $project);
    }

    public function testFindByIdHaveEmptyResult(): void
    {
        $project = $this->table->findById(TestConstants::PROJECT_ID_UNUSED);

        self::assertSame([], $project);
    }

    public function testCanFindAll(): void
    {
        $project = $this->table->findAll();

        self::assertEquals([0 => ProjectTestEntity::getDefaultProjectValue()], $project);
    }

    public function testCanFindByParticipantId(): void
    {
        $project = $this->table->findByParticipantId(TestConstants::PARTICIPANT_ID);

        self::assertEquals(ProjectTestEntity::getDefaultProjectValue(), $project);
    }
}
