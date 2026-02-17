<?php declare(strict_types=1);

namespace Exdrals\Identity\Infrastructure\Service\Account;

use DateTimeImmutable;
use Exdrals\Core\Mailing\Domain\EmailType;
use Exdrals\Core\Shared\Utils\UuidFactoryInterface;
use Exdrals\Identity\Domain\AccountActivation;
use Exdrals\Identity\Domain\Exception\DuplicateEMailException;
use Exdrals\Identity\Infrastructure\Persistence\Repository\AccountActivationRepositoryInterface;
use Exdrals\Identity\Infrastructure\Service\Token\ActivationTokenService;

readonly class AccountRegisterService implements AccountRegisterServiceInterface
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
