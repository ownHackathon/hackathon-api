<?php declare(strict_types=1);

namespace Test\Unit\App\Service;

use App\Repository\EventRepository;
use App\Service\Event\EventService;
use App\Service\Event\EventServiceFactory;
use Core\Hydrator\ReflectionHydrator;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Test\Unit\Mock\MockContainer;
use Test\Unit\Mock\Table\MockEventTable;

class EventServiceFactoryTest extends AbstractService
{
    public function testCanCreateEventService(): void
    {
        $container = new MockContainer([
            EventRepository::class => new MockEventTable(),
            ReflectionHydrator::class => $this->hydrator,
            DateTimeFormatterStrategy::class => $this->dateTimeFormatterStrategy,
        ]);

        $factory = new EventServiceFactory();

        $service = $factory($container);

        self::assertInstanceOf(EventService::class, $service);
    }
}
