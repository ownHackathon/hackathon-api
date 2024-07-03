<?php declare(strict_types=1);

namespace App\Middleware\Authentication;

use App\Dto\System\SimpleMessageDto;
use App\Entity\User;
use App\Service\Authentication\LoginAuthenticationService;
use App\Service\User\UserService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
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

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $name = $data['username'];
        $password = $data['password'];

        $user = $this->userService->findByName($name);

        if (!($user instanceof User) || !$this->authService->isUserDataCorrect($user, $password)) {
            return new JsonResponse(new SimpleMessageDto('Login failed'), HTTP::STATUS_UNAUTHORIZED);
        }

        return $handler->handle(
            $request->withAttribute(User::AUTHENTICATED_USER, $user)
        );
    }
}
