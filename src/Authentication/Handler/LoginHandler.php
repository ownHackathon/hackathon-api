<?php declare(strict_types=1);

namespace Authentication\Handler;

use App\Entity\User;
use Authentication\Schema\LoginTokenSchema;
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
     * Returns the currently valid token or null
     */
    #[OA\Get(path: '/api/login', tags: ['User Control'])]
    #[OA\Response(
        response: HTTP::STATUS_OK,
        description: 'Success',
        content: new OA\JsonContent(ref: LoginTokenSchema::class)
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
