<?php declare(strict_types=1);

namespace App\Middleware\Topic;

use App\Entity\Topic;
use App\Exception\DuplicateNameHttpException;
use App\Exception\HttpException;
use App\Hydrator\ReflectionHydrator;
use App\Service\Topic\TopicPoolService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Ramsey\Uuid\UuidInterface;

readonly class TopicCreateSubmitMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TopicPoolService $topicPoolService,
        private ReflectionHydrator $hydrator,
        private UuidInterface $uuid,
    ) {
    }

    /**
     * @throws DuplicateNameHttpException|HttpException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $topic = $this->hydrator->hydrate($data, Topic::class);

        $existTopic = $this->topicPoolService->findByTopic($topic->topic);

        if ($existTopic instanceof Topic) {
            throw new DuplicateNameHttpException(['topic' => ['topic' => 'The Topic is already present']]);
        }

        $topic = $topic->with(uuid: $this->uuid);

        $this->topicPoolService->insert($topic);

        return $handler->handle($request->withAttribute(Topic::class, $topic));
    }
}
