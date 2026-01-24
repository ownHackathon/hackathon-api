<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware\Account;

use DateTimeImmutable;
use Exdrals\Identity\Domain\AccountActivation;
use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Infrastructure\Persistence\Repository\Account\AccountActivationRepositoryInterface;
use Exdrals\Identity\Infrastructure\Service\Account\AccountService;
use Exdrals\Identity\Infrastructure\Service\Token\ActivationTokenService;
use Exdrals\Mailing\Domain\EmailType;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Exdrals\Shared\Utils\UuidFactoryInterface;

readonly class RegisterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AccountService $accountService,
        private AccountActivationRepositoryInterface $accountActivationRepository,
        private ActivationTokenService $activationTokenService,
        private UuidFactoryInterface $uuid,
        private LoggerInterface $logger,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var EmailType $email */
        $email = $request->getAttribute(EmailType::class);

        if (!$this->accountService->isEmailAvailable($email)) {
            $this->logger->warning(IdentityLogMessage::ACCOUNT_ALREADY_EXISTS, [
                'email:' => $email->toString(),
            ]);

            $this->accountService->sendTokenForPasswordChange($email);

            return $handler->handle($request);
        }

        $activation = new AccountActivation(
            id: null,
            email: $email,
            token: $this->uuid->uuid7(),
            createdAt: new DateTimeImmutable()
        );

        $this->accountActivationRepository->insert($activation);

        $this->activationTokenService->sendEmail($activation);

        return $handler->handle($request);
    }
}
