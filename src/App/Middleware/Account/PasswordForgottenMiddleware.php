<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account;

use ownHackathon\App\Service\Account\AccountService;
use ownHackathon\Core\Type\Email;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

readonly class PasswordForgottenMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountService $accountService,
        private LoggerInterface $logger,
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

        $this->logger->alert('New password requested for non-existent account', [
            'email:' => $email->toString(),
        ]);

        return $handler->handle($request);
    }
}
