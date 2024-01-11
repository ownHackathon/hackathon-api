<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Hydrator\ReflectionHydrator;
use App\Service\Project\ProjectService;
use App\Service\Project\ProjectServiceFactory;
use App\Table\ProjectTable;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Test\Unit\Mock\MockContainer;
use Test\Unit\Mock\Table\MockProjectTable;

class ProjectServiceFactoryTest extends AbstractService
{
    public function testCanCreateProjectService(): void
    {
        $container = new MockContainer([
            ProjectTable::class => new MockProjectTable(),
            ReflectionHydrator::class => $this->hydrator,
            DateTimeFormatterStrategy::class => $this->dateTimeFormatterStrategy,
        ]);

        $factory = new ProjectServiceFactory();

        $service = $factory($container);

        self::assertInstanceOf(ProjectService::class, $service);
    }
}
