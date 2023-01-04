<?php declare(strict_types=1);

namespace App\Test\Middleware\Event;

use App\Middleware\Event\EventNameMiddleware;
use App\Test\Middleware\AbstractMiddlewareTest;
use App\Test\Mock\Service\MockEventServie;
use App\Test\Mock\TestConstants;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\InvalidArgumentException;

class EventNameMiddlewareTest extends AbstractMiddlewareTest
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testReturnResponseInterface(): void
    {
        $middleware = new EventNameMiddleware(new MockEventServie());

        $response = $middleware->process(
            $this->request->withAttribute('eventName', TestConstants::EVENT_TITLE),
            $this->handler
        );

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testThrowInvalidArgumentException(): void
    {
        $middleware = new EventNameMiddleware(new MockEventServie());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(HTTP::STATUS_BAD_REQUEST);

        $response = $middleware->process(
            $this->request->withAttribute('eventName', 'fail'),
            $this->handler
        );
    }
}
