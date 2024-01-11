<?php declare(strict_types=1);

namespace App\Middleware\Topic;

use App\Dto\Topic\TopicListDto;
use App\Service\Topic\TopicPoolService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class TopicListAvailableMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TopicPoolService $topicPoolService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $topics = $this->topicPoolService->findAvailable();
        $topics = new TopicListDto($topics);

        return $handler->handle($request->withAttribute(TopicListDto::class, $topics));
    }
}
