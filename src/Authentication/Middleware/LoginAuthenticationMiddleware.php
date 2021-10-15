<?php declare(strict_types=1);

namespace Authentication\Middleware;

use App\Model\User;
use App\Service\UserService;
use Authentication\Service\LoginAuthenticationService;
use Mezzio\Flash\FlashMessageMiddleware;
use Mezzio\Flash\FlashMessagesInterface;
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

        /** @var FlashMessagesInterface $flashMessage */
        $flashMessage = $request->getAttribute(FlashMessageMiddleware::FLASH_ATTRIBUTE);

        $name = $data['name'];
        $password = $data['password'];

        $user = $this->userService->findByName($name);

        if (!$this->authService->isUserDataCorrect($user, $password)) {
            $flashMessage->flashNow('error', 'Benutzername und/oder Passwort nicht korrekt.', 0);
            return $handler->handle($request);
        }

        $flashMessage->flash('info', 'Erfolgreich angemeldet');
        return $handler->handle(
            $request->withAttribute(User::class, $user)
        );
    }
}
