<?php declare(strict_types=1);

namespace App\Test\Middleware\Event;

use App\Middleware\Event\EventListMiddleware;
use App\Test\Middleware\AbstractMiddlewareTest;
use App\Test\Mock\Service\MockEventService;
use Psr\Http\Message\ResponseInterface;

class EventListMiddlewareTest extends AbstractMiddlewareTest
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCanFindAllEventUnsorted():void
    {
        $middleware = new EventListMiddleware(new MockEventService());

        $response = $middleware->process($this->request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testCanFindAllEventSortedASC():void
    {
        $middleware = new EventListMiddleware(new MockEventService());

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

    public function testCanFindAllEventSortedDESC():void
    {
        $middleware = new EventListMiddleware(new MockEventService());

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
