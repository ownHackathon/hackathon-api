<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware\Event;

use App\Middleware\Event\EventNameMiddleware;
use Test\Unit\App\Middleware\AbstractMiddleware;
use Test\Unit\App\Mock\Service\MockEventService;
use Test\Unit\App\Mock\TestConstants;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\InvalidArgumentException;

class EventNameMiddlewareTest extends AbstractMiddleware
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testReturnResponseInterface(): void
    {
        $middleware = new EventNameMiddleware(new MockEventService());

        $response = $middleware->process(
            $this->request->withAttribute('eventName', TestConstants::EVENT_TITLE),
            $this->handler
        );

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testThrowInvalidArgumentException(): void
    {
        $middleware = new EventNameMiddleware(new MockEventService());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(HTTP::STATUS_BAD_REQUEST);

        $response = $middleware->process(
            $this->request->withAttribute('eventName', 'fail'),
            $this->handler
        );
    }
}
