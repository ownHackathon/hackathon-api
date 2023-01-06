<?php declare(strict_types=1);

namespace App\Test\Middleware\Event;

use App\Middleware\Event\EventMiddleware;
use App\Test\Middleware\AbstractMiddlewareTest;
use App\Test\Mock\Service\MockEventService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\InvalidArgumentException;

class EventMiddlewareTest extends AbstractMiddlewareTest
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testReturnResponseInterface(): void
    {
        $middleware = new EventMiddleware(new MockEventService());

        $response = $middleware->process($this->request->withAttribute('eventId', 1), $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testThrowInvalidArgumentException(): void
    {
        $middleware = new EventMiddleware(new MockEventService());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(HTTP::STATUS_BAD_REQUEST);

        $response = $middleware->process($this->request->withAttribute('eventId', 2), $this->handler);
    }
}
