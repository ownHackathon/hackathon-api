<?php declare(strict_types=1);

namespace Test\Unit\App\Handler\Topic;

use App\Entity\Topic;
use App\Handler\Topic\TopicListAvailableHandler;
use Laminas\Diactoros\Response\JsonResponse;
use Test\Data\Entity\TopicTestEntity;
use Test\Unit\Core\Handler\AbstractHandler;

class TopicListAvailableHandlerTest extends AbstractHandler
{
    public function testReturnJsonResponseWithOneOrMoreItems(): void
    {
        $handler = new TopicListAvailableHandler();

        $topicList = [new Topic(...TopicTestEntity::getDefaultTopicValue())];

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
