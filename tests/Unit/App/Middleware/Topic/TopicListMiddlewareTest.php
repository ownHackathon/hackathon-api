<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware\Topic;

use App\Middleware\Topic\TopicListAvailableMiddleware;
use Test\Unit\App\Middleware\AbstractMiddleware;
use Test\Unit\App\Mock\Service\MockTopicPoolService;
use Psr\Http\Message\ResponseInterface;

class TopicListMiddlewareTest extends AbstractMiddleware
{
    public function testCanFindAllAvailableTopic(): void
    {
        $middleware = new TopicListAvailableMiddleware(new MockTopicPoolService());

        $response = $middleware->process($this->request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
