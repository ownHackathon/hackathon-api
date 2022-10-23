<?php declare(strict_types=1);

namespace App\Test\Service;

use App\Model\Project;
use App\Service\ProjectService;
use App\Table\ProjectTable;

class ProjectServiceTest extends AbstractServiceTest
{
    public function testCanFindById(): void
    {
        $table = $this->createMock(ProjectTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($this->fetchResult);

        $service = new ProjectService($table, $this->hydrator);

        $project = $service->findById(1);

        $this->assertInstanceOf(Project::class, $project);
    }

    public function testCanNotFindById(): void
    {
        $table = $this->createMock(ProjectTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(false);

        $service = new ProjectService($table, $this->hydrator);

        $project = $service->findById(1);

        $this->assertNull($project);
    }

    public function testCanFindByParticipantId(): void
    {
        $table = $this->createMock(ProjectTable::class);

        $table->expects($this->once())
            ->method('findByParticipantId')
            ->with(1)
            ->willReturn($this->fetchResult);

        $service = new ProjectService($table, $this->hydrator);

        $project = $service->findByParticipantId(1);

        $this->assertInstanceOf(Project::class, $project);
    }
}
