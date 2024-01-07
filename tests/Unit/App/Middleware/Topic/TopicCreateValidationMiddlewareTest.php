<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware\Topic;

use App\Exception\InvalidArgumentHttpException;
use App\Middleware\Topic\TopicCreateValidationMiddleware;
use Test\Unit\App\Middleware\AbstractMiddleware;
use Test\Unit\App\Mock\Validator\MockTopicCreateValidator;
use Psr\Http\Message\ResponseInterface;

class TopicCreateValidationMiddlewareTest extends AbstractMiddleware
{
    private array $topicData
        = [
            'topic' => 'topic',
            'description' => 'This is the one and only Description',
        ];

    private TopicCreateValidationMiddleware $middleware;

    public function setUp(): void
    {
        $this->middleware = new TopicCreateValidationMiddleware(new MockTopicCreateValidator());
        parent::setUp();
    }

    public function testValidateTopicData(): void
    {
        $response = $this->middleware->process($this->request->withParsedBody($this->topicData), $this->handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testValidateTopicDataThrowException(): void
    {
        $this->expectException(InvalidArgumentHttpException::class);

        $this->middleware->process($this->request->withParsedBody([]), $this->handler);
    }
}
