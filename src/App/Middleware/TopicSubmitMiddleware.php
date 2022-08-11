<?php declare(strict_types=1);

namespace App\Middleware;

use App\Model\Topic;
use App\Service\TopicPoolService;
use Laminas\Hydrator\ClassMethodsHydrator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TopicSubmitMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly TopicPoolService $topicPoolService,
        private readonly ClassMethodsHydrator $hydrator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $validationMessages = $request->getAttribute('validationMessages');

        if (null !== $validationMessages) {
            return $handler->handle($request);
        }

        $data = $request->getParsedBody();
        $topic = $this->hydrator->hydrate($data, new Topic());

        $existTopic = $this->topicPoolService->findByTopic($topic->getTopic());

        if ($existTopic instanceof Topic) {
            $validationMessages['topic']['alreadyAvailable'] = 'Das Thema ist ungültig';
            $request = $request->withAttribute('validationMessages', $validationMessages);
        } else {
            $this->topicPoolService->insert($topic);
        }

        return $handler->handle($request->withAttribute(Topic::class, $topic));
    }
}
