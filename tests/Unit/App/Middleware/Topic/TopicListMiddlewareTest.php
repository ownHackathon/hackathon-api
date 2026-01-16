<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware\Topic;

use App\Middleware\Topic\TopicListAvailableMiddleware;
use Psr\Http\Message\ResponseInterface;
use Test\Unit\Core\Middleware\AbstractMiddleware;
use Test\Unit\Mock\Service\MockTopicPoolService;

class TopicListMiddlewareTest extends AbstractMiddleware
{
    public function testCanFindAllAvailableTopic(): void
    {
        $middleware = new TopicListAvailableMiddleware(new MockTopicPoolService());

        $response = $middleware->process($this->request, $this->handler);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }
}
