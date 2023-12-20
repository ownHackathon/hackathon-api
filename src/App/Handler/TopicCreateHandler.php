<?php declare(strict_types=1);

namespace App\Handler;

use Administration\Service\EMail\EMailServiceInterface;
use App\Dto\TopicCreateDto;
use App\Dto\TopicCreateErrorDto;
use App\Dto\TopicDto as TopicSubmit;
use App\Entity\Topic;
use App\Hydrator\ReflectionHydrator;
use Authentication\Dto\MessageDto;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class TopicCreateHandler implements RequestHandlerInterface
{
    public function __construct(
        private ReflectionHydrator $hydrator,
        private EMailServiceInterface $mailService,
    ) {
    }

    /**
     *  Create a new hackathon topic
     */
    #[OA\Post(path: '/api/topic', tags: ['Topics'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: TopicCreateDto::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_CREATED,
        description: 'Topic created',
        content: new OA\JsonContent(ref: TopicSubmit::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_UNAUTHORIZED,
        description: 'Incorrect authorization or expired',
        content: new OA\JsonContent(ref: MessageDto::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_BAD_REQUEST,
        description: 'Incorrect Request Data',
        content: new OA\JsonContent(ref: TopicCreateErrorDto::class)
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $topic = $request->getAttribute(Topic::class);

        $this->mailService->send($topic);

        $topic = new TopicSubmit($this->hydrator->extract($topic));

        return new JsonResponse($topic, HTTP::STATUS_CREATED);
    }
}
