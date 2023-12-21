<?php declare(strict_types=1);

namespace App\Test\Middleware\Event;

use App\Hydrator\ReflectionHydrator;
use App\Middleware\Event\EventCreateMiddleware;
use App\Middleware\Event\EventCreateMiddlewareFactory;
use App\Service\EventService;
use App\Test\Middleware\AbstractMiddleware;
use App\Test\Mock\MockContainer;
use App\Test\Mock\Service\MockEventService;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;

class EventCreateMiddlewareFactoryTest extends AbstractMiddleware
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCanCreateEventMiddleware(): void
    {
        $container = new MockContainer(
            [
                EventService::class => new MockEventService(),
                ReflectionHydrator::class => new ReflectionHydrator(),
                DateTimeFormatterStrategy::class => new DateTimeFormatterStrategy(),
            ]
        );

        $middleware = (new EventCreateMiddlewareFactory())($container);

        $this->assertInstanceOf(EventCreateMiddleware::class, $middleware);
    }
}
