<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware\Account;

use Exdrals\Mailing\Domain\EmailType;
use Exdrals\Identity\Infrastructure\Service\Account\AccountService;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shared\Domain\Enum\Message\LogMessage;
use Shared\Domain\Enum\Message\StatusMessage;
use Shared\Domain\Exception\HttpHandledInvalidArgumentAsSuccessException;

readonly class PasswordForgottenMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountService $accountService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var EmailType $email */
        $email = $request->getAttribute(EmailType::class);

        if (!$this->accountService->isEmailAvailable($email)) {
            $this->accountService->sendTokenForPasswordChange($email);
            return $handler->handle($request);
        }

        throw new HttpHandledInvalidArgumentAsSuccessException(
            LogMessage::PASSWORD_REQUEST_MISSING_ACCOUNT,
            StatusMessage::INVALID_DATA,
            [
                'email:' => $email->toString(),
            ],
            Level::Alert
        );
    }
}
