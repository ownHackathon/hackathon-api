<?php declare(strict_types=1);

namespace App\Handler;

use App\DTO\TopicList;
use App\Hydrator\ReflectionHydrator;
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

    #[OA\Get(
        path: '/api/topics/available',
        tags: ['Topics'],
        responses: [
            new OA\Response(
                response: HTTP::STATUS_OK,
                description: 'Success',
                content: new OA\JsonContent()
            ),
            new OA\Response(
                response: HTTP::STATUS_UNAUTHORIZED,
                description: 'Incorrect authorization or expired'
            ),
        ]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getAttribute('availableTopics');
        $data = $this->hydrator->extractList($data);
        $data = new TopicList($data);

        return new JsonResponse($data, HTTP::STATUS_OK);
    }
}
