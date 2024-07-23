<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware\Topic;

use App\Middleware\Topic\TopicCreateSubmitMiddleware;
use Core\Exception\DuplicateNameHttpException;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Test\Data\Entity\TopicTestEntity;
use Test\Data\TestConstants;
use Test\Unit\App\Middleware\AbstractMiddleware;
use Test\Unit\Mock\Service\MockTopicPoolService;

class TopicCreateSubmitMiddlewareTest extends AbstractMiddleware
{
    private TopicCreateSubmitMiddleware $middleware;

    public function setUp(): void
    {
        parent::setUp();
        $this->middleware = new TopicCreateSubmitMiddleware(
            new MockTopicPoolService(),
            $this->hydrator,
            Uuid::uuid7()
        );
    }

    public function testHasCreateNewTopicAndReturnResponse(): void
    {
        $response = $this->middleware->process(
            $this->request->withParsedBody(['topic' => TestConstants::TOPIC_TITLE_CREATE] + TopicTestEntity::getDefaultTopicValue()),
            $this->handler
        );

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testTopicIsDuplicatedAndThrowException(): void
    {
        $data = ['topic' => TestConstants::TOPIC_TITLE] + TopicTestEntity::getDefaultTopicValue();
        self::expectException(DuplicateNameHttpException::class);

        $this->middleware->process($this->request->withParsedBody($data), $this->handler);
    }
}
