<?php declare(strict_types=1);

namespace App\Handler;

use App\DTO\TopicCreate;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TopicCreateHandler implements RequestHandlerInterface
{
    #[OA\Post(
        path: '/api/topic',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: '#/components/schemas/TopicCreate')
        ),
        tags: ['Topics'],
        responses: [
            new OA\Response(
                response: HTTP::STATUS_CREATED,
                description: 'Topic created',
                content: new OA\JsonContent(ref: '#/components/schemas/Topic')
            ),
            new OA\Response(
                response: HTTP::STATUS_UNAUTHORIZED,
                description: 'Incorrect authorization or expired'
            ),
            new OA\Response(
                response: HTTP::STATUS_BAD_REQUEST,
                description: 'Incorrect Request Data'
            ),
        ]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getAttribute(TopicCreate::class);

        return new JsonResponse($data, HTTP::STATUS_CREATED);
    }
}
