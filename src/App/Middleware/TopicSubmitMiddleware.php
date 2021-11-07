<?php declare(strict_types=1);

namespace App\Middleware;

use App\Model\Topic;
use App\Service\TopicPoolService;
use Laminas\Hydrator\ClassMethodsHydrator;
use Mezzio\Flash\FlashMessageMiddleware;
use Mezzio\Flash\FlashMessagesInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TopicSubmitMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TopicPoolService $topicPoolService,
        private ClassMethodsHydrator $hydrator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var FlashMessagesInterface $flashMessage */
        $flashMessage = $request->getAttribute(FlashMessageMiddleware::FLASH_ATTRIBUTE);

        $data = $request->getParsedBody();
        $topic = $this->hydrator->hydrate($data, new Topic());

        if (empty($data['topic'])) {
            $flashMessage->flashNow('error', 'Thema darf nicht leer sein.', 0);
        } elseif ($this->topicPoolService->isTopic($data['topic'])) {
            $flashMessage->flashNow('error', 'Das Thema konnten wir leider nicht annehmen.', 0);
        } else {
            $this->topicPoolService->insert($topic);
            $flashMessage->flashNow('info', 'Danke für den Themenvorschlag', 0);
        }

        return $handler->handle($request->withAttribute(Topic::class, $topic));
    }
}
