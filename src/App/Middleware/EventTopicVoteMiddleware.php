<?php declare(strict_types=1);

namespace App\Middleware;

use App\Model\Event;
use App\Model\Topic;
use App\Service\TopicPoolService;
use App\Service\TopicVoterService;
use DateTime;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventTopicVoteMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TopicPoolService $topicPoolService,
        private TopicVoterService $topicVoterService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Event $event */
        $event = $request->getAttribute(Event::class);

        $topic = $this->topicPoolService->findByEventId($event->getId());

        if ($topic instanceof Topic) {
            return $handler->handle($request);
        }

        $time = new DateTime();

        if ($time >= $event->getStartTime()) {
            $topics = $request->getAttribute('topics');
            $topic = $this->topicVoterService->getSelectRandomlyTopic($topics);
            $topic->setEventId($event->getId());

            $this->topicPoolService->updateEventId($topic);
        }

        return $handler->handle($request);
    }
}
