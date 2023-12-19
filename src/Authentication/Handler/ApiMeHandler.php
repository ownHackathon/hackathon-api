<?php declare(strict_types=1);

namespace Authentication\Handler;

use App\Entity\User;
use Authentication\Schema\ApiMeSchema;
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
class ApiMeHandler implements RequestHandlerInterface
{
    /**
     * Ask if the user is logged in
     */
    #[OA\Get(path: '/api/me', tags: ['User Control'])]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: 'Success',
        content: new OA\JsonContent(ref: '#/components/schemas/ApiMeSchema')
    )]
    #[OA\Response(
        response: HTTP::STATUS_UNAUTHORIZED,
        description: 'Incorrect authorization or expired'
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(User::USER_ATTRIBUTE);

        if (!($user instanceof User)) {
            return new JsonResponse([], HTTP::STATUS_OK);
        }

        return new JsonResponse(new ApiMeSchema($user), HTTP::STATUS_OK);
    }
}
