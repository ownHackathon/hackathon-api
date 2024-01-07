<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware\Event;

use App\Middleware\Event\EventListMiddleware;
use Test\Unit\App\Middleware\AbstractMiddleware;
use Test\Unit\App\Mock\Service\MockEventService;
use Test\Unit\App\Mock\Service\MockUserService;
use Psr\Http\Message\ResponseInterface;

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

        $this->assertInstanceOf(ResponseInterface::class, $response);
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

        $this->assertInstanceOf(ResponseInterface::class, $response);
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

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
