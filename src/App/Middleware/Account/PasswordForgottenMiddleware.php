<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ownHackathon\App\Service\Account\AccountService;
use ownHackathon\Core\Type\Email;

readonly class PasswordForgottenMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountService $accountService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Email $email */
        $email = $request->getAttribute(Email::class);

        if (!$this->accountService->isEmailAvailable($email)) {
            $this->accountService->sendTokenForPasswordChange($email);
            return $handler->handle($request);
        }

        return $handler->handle($request);
    }
}
