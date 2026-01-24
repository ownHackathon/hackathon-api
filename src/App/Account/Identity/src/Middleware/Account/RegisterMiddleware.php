<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Middleware\Account;

use Exdrals\Account\Identity\Domain\AccountActivation;
use Exdrals\Account\Identity\Domain\Email;
use Exdrals\Account\Identity\Infrastructure\Persistence\Repository\Account\AccountActivationRepositoryInterface;
use Exdrals\Account\Identity\Infrastructure\Service\Account\AccountService;
use Exdrals\Account\Identity\Infrastructure\Service\Token\ActivationTokenService;
use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Shared\Domain\Enum\Message\LogMessage;
use Shared\Utils\UuidFactoryInterface;

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
        /** @var Email $email */
        $email = $request->getAttribute(Email::class);

        if (!$this->accountService->isEmailAvailable($email)) {
            $this->logger->warning(LogMessage::ACCOUNT_ALREADY_EXISTS->value, [
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
