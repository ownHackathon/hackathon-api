<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Service\Account;

use DateTimeImmutable;
use Exdrals\Identity\Domain\AccountActivation;
use Exdrals\Identity\Domain\Exception\DuplicateEMailException;
use Exdrals\Identity\Infrastructure\Service\Token\ActivationTokenService;
use Exdrals\Mailing\Domain\EmailType;
use Exdrals\Shared\Infrastructure\Persistence\Repository\Account\AccountActivationRepositoryInterface;
use Exdrals\Shared\Utils\UuidFactoryInterface;

readonly class AccountRegisterService
{
    public function __construct(
        private AccountService $accountService,
        private AccountActivationRepositoryInterface $accountActivationRepository,
        private ActivationTokenService $activationTokenService,
        private UuidFactoryInterface $uuid,
    ) {
    }

    public function register(EmailType $email): void
    {
        if (!$this->accountService->isEmailAvailable($email)) {
            $this->accountService->sendTokenForPasswordChange($email);
            throw new DuplicateEmailException($email->toString());
        }

        $activation = new AccountActivation(
            id: null,
            email: $email,
            token: $this->uuid->uuid7(),
            createdAt: new DateTimeImmutable()
        );

        $this->accountActivationRepository->insert($activation);

        $this->activationTokenService->sendEmail($activation);
    }
}
