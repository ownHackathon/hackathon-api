<?php declare(strict_types=1);

namespace App\Test\Middleware\Topic;

use App\Middleware\Topic\TopicListAvailableMiddleware;
use App\Test\Middleware\AbstractMiddlewareTest;
use App\Test\Mock\Service\MockTopicPoolService;
use Psr\Http\Message\ResponseInterface;

class TopicListMiddlewareTest extends AbstractMiddlewareTest
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCanFindAllAvailableTopic(): void
    {
        $middleware = new TopicListAvailableMiddleware(new MockTopicPoolService());

        $response = $middleware->process($this->request, $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
