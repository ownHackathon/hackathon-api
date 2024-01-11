<?php declare(strict_types=1);

namespace Test\Unit\App\Handler;

use App\Entity\Topic;
use App\Handler\Topic\TopicListAvailableHandler;
use Laminas\Diactoros\Response\JsonResponse;

class TopicListAvailableHandlerTest extends AbstractHandler
{
    public function testReturnJsonResponseWithOneOrMoreItems(): void
    {
        $handler = new TopicListAvailableHandler();

        $topicList = [new Topic()];

        $response = $handler->handle($this->request->withAttribute('availableTopics', $topicList));

        self::assertInstanceOf(JsonResponse::class, $response);
    }

    public function testReturnJsonResponseWithoutItems(): void
    {
        $handler = new TopicListAvailableHandler();

        $topicList = [];

        $response = $handler->handle($this->request->withAttribute('availableTopics', $topicList));

        self::assertInstanceOf(JsonResponse::class, $response);
    }
}
