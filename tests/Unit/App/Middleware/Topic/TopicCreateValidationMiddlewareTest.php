<?php declare(strict_types=1);

namespace Test\Unit\App\Middleware\Topic;

use App\Middleware\Topic\TopicCreateValidationMiddleware;
use Core\Exception\InvalidArgumentHttpException;
use Psr\Http\Message\ResponseInterface;
use Test\Unit\App\Middleware\AbstractMiddleware;
use Test\Unit\Mock\Validator\MockTopicCreateValidator;

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
        $response = $this->middleware->process(
            $this->request->withParsedBody($this->topicData),
            $this->handler
        );

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testValidateTopicDataThrowException(): void
    {
        self::expectException(InvalidArgumentHttpException::class);

        $this->middleware->process($this->request->withParsedBody([]), $this->handler);
    }
}
