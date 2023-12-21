<?php declare(strict_types=1);

namespace App\Handler;

use App\Dto\TopicListDto;
use App\Hydrator\ReflectionHydrator;
use Authentication\Dto\SimpleMessageDto;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class TopicListAvailableHandler implements RequestHandlerInterface
{
    public function __construct(
        private ReflectionHydrator $hydrator,
    ) {
    }

    /**
     * Returns a list of all available topics that are available for a new event.
     */
    #[OA\Get(path: '/api/topics/available', tags: ['Topics'])]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: 'Success',
        content: new OA\JsonContent(ref: TopicListDto::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_UNAUTHORIZED,
        description: 'Incorrect authorization or expired',
        content: new OA\JsonContent(ref: SimpleMessageDto::class)
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getAttribute('availableTopics');

        $data = $this->hydrator->extractList($data);

        $data = new TopicListDto($data);

        return new JsonResponse($data, HTTP::STATUS_OK);
    }
}
