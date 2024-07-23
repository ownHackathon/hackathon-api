<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware\Event;

use App\Middleware\Event\EventMiddleware;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Test\Data\TestConstants;
use Test\Unit\Core\Middleware\AbstractMiddleware;
use Test\Unit\Mock\Service\MockEventService;

class EventMiddlewareTest extends AbstractMiddleware
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testReturnResponseInterface(): void
    {
        $middleware = new EventMiddleware(new MockEventService());

        $response = $middleware->process($this->request->withAttribute('eventId', 1), $this->handler);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testThrowInvalidArgumentException(): void
    {
        $middleware = new EventMiddleware(new MockEventService());

        self::expectException(InvalidArgumentException::class);
        self::expectExceptionCode(HTTP::STATUS_BAD_REQUEST);

        $middleware->process(
            $this->request->withAttribute('eventId', TestConstants::EVENT_ID_THROW_EXCEPTION),
            $this->handler
        );
    }
}
