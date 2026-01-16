<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware\Event;

use App\Middleware\Event\EventNameMiddleware;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Test\Data\TestConstants;
use Test\Unit\Core\Middleware\AbstractMiddleware;
use Test\Unit\Mock\Service\MockEventService;

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

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testThrowInvalidArgumentException(): void
    {
        $middleware = new EventNameMiddleware(new MockEventService());

        self::expectException(InvalidArgumentException::class);
        self::expectExceptionCode(HTTP::STATUS_BAD_REQUEST);

        $middleware->process(
            $this->request->withAttribute('eventName', TestConstants::EVENT_TITLE_THROW_EXCEPTION),
            $this->handler
        );
    }
}
