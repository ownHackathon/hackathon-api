<?php declare(strict_types=1);

namespace Authentication\Middleware;

use App\Entity\User;
use App\Service\UserService;
use Authentication\Service\LoginAuthenticationService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class LoginAuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private UserService $userService,
        private LoginAuthenticationService $authService,
    ) {
    }

    #[OA\Post(
        path: '/api/login',
        requestBody: new OA\RequestBody(
            description: 'User data for authentication',
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: 'username',
                                type: 'string',
                            ),
                            new OA\Property(
                                property: 'password',
                                type: 'string',
                            )
                        ],
                        type: 'object'
                    )
                )
            ]
        ),
        tags: ['User Control'],
        responses: [
            new OA\Response(
                response: HTTP::STATUS_UNAUTHORIZED,
                description: 'User was not authenticated'
            ),
            new OA\Response(
                response: HTTP::STATUS_OK,
                description: 'Success',
                content: [
                    new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: 'token',
                                description: 'token to authenticate a user',
                                oneOf: [
                                    new OA\Schema(
                                        description: 'the token if the user could be authenticated',
                                        type: 'string'
                                    ),
                                    new OA\Schema(
                                        description: 'otherwise the token will be null',
                                        type: 'null'
                                    )
                                ]
                            )
                        ]
                    )
                ]
            ),
        ]
    )]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $validationMessages = $request->getAttribute('validationMessages');

        if (null !== $validationMessages) {
            return $handler->handle($request);
        }

        $name = $data['username'];
        $password = $data['password'];

        $user = $this->userService->findByName($name);

        if (!$this->authService->isUserDataCorrect($user, $password)) {
            return new JsonResponse(['message' => 'Login failed'], HTTP::STATUS_UNAUTHORIZED);
        }

        return $handler->handle(
            $request->withAttribute(User::USER_ATTRIBUTE, $user)
        );
    }
}
