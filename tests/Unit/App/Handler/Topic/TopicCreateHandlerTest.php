<?php declare(strict_types=1);

namespace Test\Unit\App\Handler\Topic;

use App\Entity\Topic;
use App\Handler\Topic\TopicCreateHandler;
use Laminas\Diactoros\Response\JsonResponse;
use Test\Data\Entity\TopicTestEntity;
use Test\Unit\App\Handler\AbstractHandler;
use Test\Unit\Mock\Service\MockTopicCreateEMailService;

class TopicCreateHandlerTest extends AbstractHandler
{
    public function testReturnJSonWithTopicData(): void
    {
        $handler = new TopicCreateHandler(new MockTopicCreateEMailService());

        $response = $handler->handle(
            $this->request->withAttribute(Topic::class, new Topic(...TopicTestEntity::getDefaultTopicValue()))
        );

        $responseData = $response->getBody()->getContents();

        $responseDataAsArray = json_decode($responseData, true);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertIsString($responseData);
        self::assertJson($responseData);
        self::assertIsArray($responseDataAsArray);
        self::assertArrayHasKey('topic', $responseDataAsArray);
    }
}
