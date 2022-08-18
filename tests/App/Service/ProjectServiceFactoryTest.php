<?php declare(strict_types=1);

namespace AppTest\Service;

use App\Hydrator\ReflectionHydrator;
use App\Service\ProjectService;
use App\Service\ProjectServiceFactory;
use App\Table\ProjectTable;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Psr\Container\ContainerInterface;

class ProjectServiceFactoryTest extends AbstractServiceTest
{
    public function testCanCreateProjectService(): void
    {
        $table = $this->createMock(ProjectTable::class);
        $strategy = new DateTimeFormatterStrategy();
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->exactly(3))
            ->method('get')
            ->will(
                $this->returnValueMap(
                    [
                        [ProjectTable::class, $table],
                        [ReflectionHydrator::class, $this->hydrator],
                        [DateTimeFormatterStrategy::class, $strategy],
                    ]
                )
            );

        $projectServiceFactory = new ProjectServiceFactory();

        $projectService = $projectServiceFactory($container);

        $this->assertInstanceOf(ProjectService::class, $projectService);
    }
}
