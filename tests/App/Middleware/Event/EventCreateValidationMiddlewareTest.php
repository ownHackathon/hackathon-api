<?php declare(strict_types=1);

namespace App\Middleware\Event;

use App\Test\Middleware\AbstractMiddlewareTest;
use App\Test\Mock\Validator\MockEventCreateValidator;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

class EventCreateValidationMiddlewareTest extends AbstractMiddlewareTest
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

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertNotInstanceOf(JsonResponse::class, $response);
    }

    public function testValidationIsNotValide(): void
    {
        $middleware = new EventCreateValidationMiddleware(new MockEventCreateValidator());

        $response = $middleware->process(
            $this->request->withParsedBody([false]),
            $this->handler
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
