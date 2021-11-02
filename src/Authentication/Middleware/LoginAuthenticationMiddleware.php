<?php declare(strict_types=1);

namespace Authentication\Middleware;

use App\Model\User;
use App\Service\UserService;
use Authentication\Service\LoginAuthenticationService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginAuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private UserService $userService,
        private LoginAuthenticationService $authService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $validation = $request->getAttribute('validationMessages');

        if (null !== $validation) {
            return $handler->handle($request);
        }

        $name = $data['userName'];
        $password = $data['password'];

        $user = $this->userService->findByName($name);

        if (!$this->authService->isUserDataCorrect($user, $password)) {
            return $handler->handle($request->withAttribute('validationFailure', 'Benutzername und/oder Passwort nicht korrekt.'));
        }

        return $handler->handle(
            $request->withAttribute(User::USER_ATTRIBUTE, $user)
        );
    }
}
