<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware\Topic;

use App\Exception\DuplicateNameHttpException;
use App\Middleware\Topic\TopicCreateSubmitMiddleware;
use Test\Unit\App\Middleware\AbstractMiddleware;
use Test\Unit\App\Mock\Service\MockTopicPoolService;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

class TopicCreateSubmitMiddlewareTest extends AbstractMiddleware
{
    private TopicCreateSubmitMiddleware $middleware;
    private array $topicData
        = [
            'topic' => 'topic',
            'description' => 'This is the one and only Description',
        ];

    public function setUp(): void
    {
        parent::setUp();
        $this->middleware = new TopicCreateSubmitMiddleware(new MockTopicPoolService(), $this->hydrator, Uuid::uuid4());
    }

    public function testHasCreateNewTopicAndReturnResponse(): void
    {
        $response = $this->middleware->process($this->request->withParsedBody($this->topicData), $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testTopicIsDuplicatedAndThrowException(): void
    {
        $data = $this->topicData;
        $data['topic'] = 'duplicated';

        $this->expectException(DuplicateNameHttpException::class);

        $this->middleware->process($this->request->withParsedBody($data), $this->handler);
    }
}
