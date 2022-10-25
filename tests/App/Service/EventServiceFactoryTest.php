<?php declare(strict_types=1);

namespace App\Test\Service;

use App\Hydrator\ReflectionHydrator;
use App\Service\EventService;
use App\Service\EventServiceFactory;
use App\Table\EventTable;
use App\Test\Mock\MockContainer;
use App\Test\Mock\Table\MockEventTable;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;

class EventServiceFactoryTest extends AbstractServiceTest
{
    public function testCanCreateEventService(): void
    {
        $container = new MockContainer([
            EventTable::class => new MockEventTable(),
            ReflectionHydrator::class => $this->hydrator,
            DateTimeFormatterStrategy::class => $this->strategy,
        ]);

        $factory = new EventServiceFactory();

        $service = $factory($container);

        $this->assertInstanceOf(EventService::class, $service);
    }
}
