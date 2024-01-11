<?php declare(strict_types=1);

namespace Test\Unit\App\Handler;

use App\Entity\Topic;
use App\Handler\TopicCreateHandler;
use Laminas\Diactoros\Response\JsonResponse;
use Test\Unit\Mock\Service\MockTopicCreateEMailService;

class TopicCreateHandlerTest extends AbstractHandler
{
    public function testReturnJSonWithTopicData(): void
    {
        $handler = new TopicCreateHandler(new MockTopicCreateEMailService());

        $response = $handler->handle($this->request->withAttribute(Topic::class, new Topic()));

        $responseData = $response->getBody()->getContents();

        $responseDataAsArray = json_decode($responseData, true);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertIsString($responseData);
        self::assertJson($responseData);
        self::assertIsArray($responseDataAsArray);
        self::assertArrayHasKey('topic', $responseDataAsArray);
    }
}
