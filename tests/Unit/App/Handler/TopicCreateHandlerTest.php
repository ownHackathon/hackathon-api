<?php declare(strict_types=1);

namespace Test\Unit\App\Handler;

use App\Entity\Topic;
use App\Handler\TopicCreateHandler;
use Test\Unit\App\Mock\Service\MockTopicCreateEMailService;
use Laminas\Diactoros\Response\JsonResponse;

class TopicCreateHandlerTest extends AbstractHandler
{
    public function testReturnJSonWithTopicData(): void
    {
        $handler = new TopicCreateHandler(new MockTopicCreateEMailService());

        $response = $handler->handle($this->request->withAttribute(Topic::class, new Topic()));

        $responseData = $response->getBody()->getContents();

        $responseDataAsArray = json_decode($responseData, true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertIsString($responseData);
        $this->assertJson($responseData);
        $this->assertIsArray($responseDataAsArray);
        $this->assertArrayHasKey('topic', $responseDataAsArray);
    }
}
