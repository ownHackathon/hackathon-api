<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware\Event;

use App\Middleware\Event\EventCreateMiddleware;
use Core\Entity\User;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Test\Data\Entity\UserTestEntity;
use Test\Data\TestConstants;
use Test\Unit\Core\Middleware\AbstractMiddleware;
use Test\Unit\Mock\Service\MockEventService;

class EventCreateMiddlewareTest extends AbstractMiddleware
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCreateEventAndReturnResponseInterface(): void
    {
        $middleware = new EventCreateMiddleware(new MockEventService(), $this->hydrator);
        $user = new User(...UserTestEntity::getDefaultUserValue());
        $user = $user->with(id: TestConstants::USER_ID);

        $response = $middleware->process(
            $this->request->withAttribute(User::AUTHENTICATED_USER, $user)
                ->withParsedBody(['id' => 2]),
            $this->handler
        );

        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertNotInstanceOf(JsonResponse::class, $response);
    }

    public function testEventIsPresentAndCanNotCreated(): void
    {
        $middleware = new EventCreateMiddleware(new MockEventService(), $this->hydrator);
        $user = new User(...UserTestEntity::getDefaultUserValue());
        $user = $user->with(id: TestConstants::USER_ID);

        $response = $middleware->process(
            $this->request->withAttribute(User::AUTHENTICATED_USER, $user)
                ->withParsedBody(['id' => TestConstants::USER_ID]),
            $this->handler
        );

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertSame(HTTP::STATUS_NOT_FOUND, $response->getStatusCode());
    }
}
