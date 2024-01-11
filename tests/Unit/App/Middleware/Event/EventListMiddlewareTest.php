<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware\Event;

use App\Middleware\Event\EventListMiddleware;
use Psr\Http\Message\ResponseInterface;
use Test\Unit\App\Middleware\AbstractMiddleware;
use Test\Unit\Mock\Service\MockEventService;
use Test\Unit\Mock\Service\MockUserService;

class EventListMiddlewareTest extends AbstractMiddleware
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCanFindAllEventUnsorted(): void
    {
        $middleware = new EventListMiddleware(new MockEventService(), new MockUserService());

        $response = $middleware->process($this->request, $this->handler);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testCanFindAllEventSortedASC(): void
    {
        $middleware = new EventListMiddleware(new MockEventService(), new MockUserService());

        $response = $middleware->process(
            $this->request->withQueryParams(
                [
                    'order' => 'startTime',
                    'sort' => 'ASC',
                ]
            ),
            $this->handler
        );

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testCanFindAllEventSortedDESC(): void
    {
        $middleware = new EventListMiddleware(new MockEventService(), new MockUserService());

        $response = $middleware->process(
            $this->request->withQueryParams(
                [
                    'order' => 'startTime',
                    'sort' => 'DESC',
                ]
            ),
            $this->handler
        );

        self::assertInstanceOf(ResponseInterface::class, $response);
    }
}
