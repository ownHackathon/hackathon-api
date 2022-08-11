<?php declare(strict_types=1);

namespace Authentication\Middleware;

use App\Model\User;
use App\Service\UserService;
use Authentication\Service\LoginAuthenticationService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginAuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly UserService $userService,
        private readonly LoginAuthenticationService $authService,
    ) {
    }

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
