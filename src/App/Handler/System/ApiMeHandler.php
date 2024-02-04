<?php declare(strict_types=1);

namespace App\Handler\System;

use App\Dto\System\ApiMeDto;
use App\Dto\System\SimpleMessageDto;
use App\Entity\User;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[OA\Info(version: '0.1.0', title: 'Hackathon API Overview')]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    name: 'Authorization',
    in: 'header',
    bearerFormat: 'JWT',
    scheme: 'bearer',
)
]
#[OA\OpenApi(
    openapi: '3.1.0',
    security: [['bearerAuth' => []]],
)]
readonly class ApiMeHandler implements RequestHandlerInterface
{
    #[OA\Get(
        path: '/api/user/me',
        summary: 'Returns minimal information for a logged-in user or empty',
        tags: ['User Control'],
    )]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: 'Success',
        content: new OA\JsonContent(ref: ApiMeDto::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_UNAUTHORIZED,
        description: 'Incorrect authorization or expired',
        content: new OA\JsonContent(ref: SimpleMessageDto::class)
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(User::AUTHENTICATED_USER);

        if (!($user instanceof User)) {
            return new JsonResponse([], HTTP::STATUS_OK);
        }

        return new JsonResponse(new ApiMeDto($user), HTTP::STATUS_OK);
    }
}
