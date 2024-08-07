<?php declare(strict_types=1);

namespace Core\Handler;

use Core\Dto\SimpleMessageDto;
use Core\Dto\User\LoginTokenDto;
use Core\Dto\User\LoginValidationFailureMessageDto;
use Core\Dto\User\UserLogInDataDto;
use Core\Entity\User;
use Core\Token\JwtTokenGeneratorTrait;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class LoginHandler implements RequestHandlerInterface
{
    use JwtTokenGeneratorTrait;

    public function __construct(
        private string $tokenSecret,
        private int $tokenDuration
    ) {
    }

    /**
     * Attempts to log in a user using transferred data
     */
    #[OA\Post(path: '/api/login', tags: ['User Control'])]
    #[OA\RequestBody(
        description: 'User data for authentication',
        required: true,
        content: new OA\JsonContent(ref: UserLogInDataDto::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: 'Success',
        content: [new OA\JsonContent(ref: LoginTokenDto::class)]
    )]
    #[OA\Response(
        response: HTTP::STATUS_BAD_REQUEST,
        description: 'Incorrect Request Data',
        content: [new OA\JsonContent(ref: LoginValidationFailureMessageDto::class)]
    )]
    #[OA\Response(
        response: HTTP::STATUS_UNAUTHORIZED,
        description: 'User was not authenticated',
        content: [new OA\JsonContent(ref: SimpleMessageDto::class)]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(User::AUTHENTICATED_USER);

        $token = $this->generateToken(
            $user->uuid->getHex()->toString(),
            $this->tokenSecret,
            $this->tokenDuration,
        );

        return new JsonResponse(new LoginTokenDto($token), HTTP::STATUS_OK);
    }
}
