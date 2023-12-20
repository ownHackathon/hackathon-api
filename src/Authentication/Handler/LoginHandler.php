<?php declare(strict_types=1);

namespace Authentication\Handler;

use App\Entity\User;
use Authentication\Dto\LoginTokenDto;
use Authentication\Dto\MessageDto;
use Authentication\Dto\UserLogInDataDto;
use Authentication\Service\JwtTokenGeneratorTrait;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginHandler implements RequestHandlerInterface
{
    use JwtTokenGeneratorTrait;

    public function __construct(
        private readonly string $tokenSecret,
        private readonly int $tokenDuration
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
        response: HTTP::STATUS_UNAUTHORIZED,
        description: 'User was not authenticated',
        content: [new OA\JsonContent(ref: MessageDto::class)]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(User::USER_ATTRIBUTE);
        $token = null;

        if ($user instanceof User) {
            $token = $this->generateToken(
                $user->getUuid(),
                $this->tokenSecret,
                $this->tokenDuration,
            );
        }

        return new JsonResponse(['token' => $token], HTTP::STATUS_OK);
    }
}
