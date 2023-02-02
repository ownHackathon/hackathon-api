<?php declare(strict_types=1);

namespace App\Test\Handler;

use App\Handler\TopicListAvailableHandler;
use App\Model\Topic;
use Laminas\Diactoros\Response\JsonResponse;

class TopicListAvailableHandlerTest extends AbstractHandlerTest
{

    public function testReturnJsonResponseWithOneOrMoreItems(): void
    {
        $handler = new TopicListAvailableHandler($this->hydrator);

        $topicList = [new Topic()];

        $response = $handler->handle($this->request->withAttribute('availableTopics', $topicList));

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testReturnJsonResponseWithoutItems(): void
    {
        $handler = new TopicListAvailableHandler($this->hydrator);

        $topicList = [];

        $response = $handler->handle($this->request->withAttribute('availableTopics', $topicList));

        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
