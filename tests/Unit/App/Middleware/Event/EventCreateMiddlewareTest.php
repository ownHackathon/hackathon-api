<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware\Event;

use App\Middleware\Event\EventCreateMiddleware;
use App\Entity\User;
use Test\Unit\App\Middleware\AbstractMiddleware;
use Test\Unit\App\Mock\Service\MockEventService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

class EventCreateMiddlewareTest extends AbstractMiddleware
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCreateEventAndReturnResponseInterface(): void
    {
        $middleware = new EventCreateMiddleware(new MockEventService(), $this->hydrator);
        $user = new User();
        $user->setId(1);
        $response = $middleware->process(
            $this->request->withAttribute(User::AUTHENTICATED_USER, $user)
                ->withParsedBody(['id' => 2]),
            $this->handler
        );

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertNotInstanceOf(JsonResponse::class, $response);
    }

    public function testEventIsPresentAndCanNotCreated(): void
    {
        $middleware = new EventCreateMiddleware(new MockEventService(), $this->hydrator);
        $user = new User();
        $user->setId(1);
        $response = $middleware->process(
            $this->request->withAttribute(User::AUTHENTICATED_USER, $user)
                ->withParsedBody(['id' => 1]),
            $this->handler
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(HTTP::STATUS_NOT_FOUND, $response->getStatusCode());
    }
}
