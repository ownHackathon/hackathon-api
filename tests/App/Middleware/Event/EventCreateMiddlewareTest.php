<?php declare(strict_types=1);

namespace App\Middleware\Event;

use App\Model\User;
use App\Test\Middleware\AbstractMiddlewareTest;
use App\Test\Mock\Service\MockEventServie;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

class EventCreateMiddlewareTest extends AbstractMiddlewareTest
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCreateEventAndReturnResponseInterface(): void
    {
        $middleware = new EventCreateMiddleware(new MockEventServie(), $this->hydrator);
        $user = new User();
        $user->setId(1);
        $response = $middleware->process(
            $this->request->withAttribute(User::USER_ATTRIBUTE, $user)
                ->withParsedBody(['id' => 2]),
            $this->handler
        );

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertNotInstanceOf(JsonResponse::class, $response);
    }

    public function testEventIsPresentAndCanNotCreated(): void
    {
        $middleware = new EventCreateMiddleware(new MockEventServie(), $this->hydrator);
        $user = new User();
        $user->setId(1);
        $response = $middleware->process(
            $this->request->withAttribute(User::USER_ATTRIBUTE, $user)
                ->withParsedBody(['id' => 1]),
            $this->handler
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(HTTP::STATUS_NOT_FOUND, $response->getStatusCode());
    }
}
