<?php declare(strict_types=1);

namespace App\Service;

use App\Model\Project;
use App\Table\ProjectTable;

class ProjectServiceTest extends AbstractServiceTest
{
    public function testFindByIdThrowException(): void
    {
        $table = $this->createMock(ProjectTable::class);

        $table->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(false);

        $service = new ProjectService($table, $this->hydrator);

        $this->expectException('InvalidArgumentException');

        $service->findById(1);
    }

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
