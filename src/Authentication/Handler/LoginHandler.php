<?php declare(strict_types=1);

namespace Authentication\Handler;

use App\Model\User;
use Authentication\Service\JwtTokenGeneratorTrait;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
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
