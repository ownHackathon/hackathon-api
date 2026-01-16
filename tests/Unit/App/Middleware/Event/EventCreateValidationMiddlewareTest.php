<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware\Event;

use App\Middleware\Event\EventCreateValidationMiddleware;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Test\Unit\Core\Middleware\AbstractMiddleware;
use Test\Unit\Mock\Validator\MockEventCreateValidator;

class EventCreateValidationMiddlewareTest extends AbstractMiddleware
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testValidationIsValide(): void
    {
        $middleware = new EventCreateValidationMiddleware(new MockEventCreateValidator());

        $response = $middleware->process(
            $this->request->withParsedBody([true]),
            $this->handler
        );

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertNotInstanceOf(JsonResponse::class, $response);
    }

    public function testValidationIsNotValide(): void
    {
        $middleware = new EventCreateValidationMiddleware(new MockEventCreateValidator());

        $response = $middleware->process(
            $this->request->withParsedBody([false]),
            $this->handler
        );

        self::assertInstanceOf(JsonResponse::class, $response);
    }
}
