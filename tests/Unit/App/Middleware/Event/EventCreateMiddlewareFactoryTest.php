<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware\Event;

use App\Hydrator\ReflectionHydrator;
use App\Middleware\Event\EventCreateMiddleware;
use App\Middleware\Event\EventCreateMiddlewareFactory;
use App\Service\Event\EventService;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;
use Test\Unit\App\Middleware\AbstractMiddleware;
use Test\Unit\Mock\MockContainer;
use Test\Unit\Mock\Service\MockEventService;

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

        self::assertInstanceOf(EventCreateMiddleware::class, $middleware);
    }
}
