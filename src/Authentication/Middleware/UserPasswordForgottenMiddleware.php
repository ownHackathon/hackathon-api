<?php declare(strict_types=1);

namespace Authentication\Middleware;

use App\Entity\User;
use App\Service\TokenService;
use App\Service\UserService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class UserPasswordForgottenMiddleware implements MiddlewareInterface
{
    public function __construct(
        private UserService $userService,
        private TokenService $tokenService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $user = $this->userService->findByEMail($data['email']);

        if (!$user) {
            return new JsonResponse(['message' => 'invalid E-Mai'], HTTP::STATUS_BAD_REQUEST);
        }

        $user->setToken($this->tokenService->generateToken());

        $this->userService->update($user);

        return $handler->handle($request->withAttribute(User::class, $user));
    }
}
