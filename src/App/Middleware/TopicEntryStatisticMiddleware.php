<?php declare(strict_types=1);

namespace App\Middleware;

use App\Service\TopicPoolService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TopicEntryStatisticMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TopicPoolService $topicPoolService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $this->topicPoolService->getEntriesStatistic();

        return $handler->handle($request->withAttribute('topicEntriesStatistic', $data));
    }
}
