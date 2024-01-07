<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Hydrator\ReflectionHydrator;
use App\Service\ProjectService;
use App\Service\ProjectServiceFactory;
use App\Table\ProjectTable;
use Test\Unit\App\Mock\MockContainer;
use Test\Unit\App\Mock\Table\MockProjectTable;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;

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

        $this->assertInstanceOf(ProjectService::class, $service);
    }
}
