<?php declare(strict_types=1);

namespace App\Test\Service;

use App\Hydrator\ReflectionHydrator;
use App\Service\EventService;
use App\Service\EventServiceFactory;
use App\Table\EventTable;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Psr\Container\ContainerInterface;

class EventServiceFactoryTest extends AbstractServiceTest
{
    public function testCanCreateEventService(): void
    {
        $table = $this->createMock(EventTable::class);
        $container = $this->createMock(ContainerInterface::class);

        $container->expects($this->exactly(3))
            ->method('get')
            ->will(
                $this->returnValueMap(
                    [
                        [EventTable::class, $table],
                        [ReflectionHydrator::class, $this->hydrator],
                        [DateTimeFormatterStrategy::class, new DateTimeFormatterStrategy()],
                    ]
                )
            );

        $factory = new EventServiceFactory();

        $service = $factory($container);

        $this->assertInstanceOf(EventService::class, $service);
    }
}
