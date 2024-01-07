<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Hydrator\ReflectionHydrator;
use App\Service\EventService;
use App\Service\EventServiceFactory;
use App\Table\EventTable;
use Test\Unit\App\Mock\MockContainer;
use Test\Unit\App\Mock\Table\MockEventTable;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;

class EventServiceFactoryTest extends AbstractService
{
    public function testCanCreateEventService(): void
    {
        $container = new MockContainer([
            EventTable::class => new MockEventTable(),
            ReflectionHydrator::class => $this->hydrator,
            DateTimeFormatterStrategy::class => $this->dateTimeFormatterStrategy,
        ]);

        $factory = new EventServiceFactory();

        $service = $factory($container);

        $this->assertInstanceOf(EventService::class, $service);
    }
}
