<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Entity\Project;
use App\Service\Project\ProjectService;
use InvalidArgumentException;
use Test\Unit\Mock\Table\MockProjectTable;
use Test\Data\TestConstants;

class ProjectServiceTest extends AbstractService
{
    private ProjectService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $table = new MockProjectTable();
        $this->service = new ProjectService($table, $this->hydrator);
    }

    public function testCanFindById(): void
    {
        $project = $this->service->findById(1);

        self::assertInstanceOf(Project::class, $project);
    }

    public function testCanNotFindById(): void
    {
        self::expectException(InvalidArgumentException::class);

        $this->service->findById(TestConstants::PROJECT_ID_UNUSED);
    }

    public function testCanFindByParticipantId(): void
    {
        $project = $this->service->findByParticipantId(1);

        self::assertInstanceOf(Project::class, $project);
    }

    public function testCanNotFindByParticipantId(): void
    {
        $project = $this->service->findByParticipantId(2);

        self::assertNull($project);
    }
}
