<?php declare(strict_types=1);

namespace App\Account\Identity\Infrastructure\Service\Account;

use App\Account\Identity\Application\Port\AccountRegisterServiceInterface;
use App\Account\Identity\Application\Port\ActivityLoggerInterface;
use App\Account\Identity\Application\Port\EmailHashSaltProviderInterface;
use App\Account\Identity\Domain\AccountActivation;
use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Repository\AccountActivationRepositoryInterface;
use App\Account\Identity\Infrastructure\Service\Token\ActivationTokenService;
use App\Mailing\Domain\EmailType;
use Core\Observability\EmailHasher;
use Core\SharedKernel\Utils\UuidFactoryInterface;
use DateTimeImmutable;

readonly final class AccountRegisterService implements AccountRegisterServiceInterface
{
    public function __construct(
        private AccountService $accountService,
        private AccountActivationRepositoryInterface $accountActivationRepository,
        private ActivationTokenService $activationTokenService,
        private UuidFactoryInterface $uuid,
        private ActivityLoggerInterface $activityLogger,
        private EmailHashSaltProviderInterface $emailHashSaltProvider,
    ) {
    }

    #[\Override]
    public function register(EmailType $email): void
    {
        if (!$this->accountService->isEmailAvailable($email)) {
            $this->accountService->sendTokenForPasswordChange($email);
            return;
        }

        $activation = new AccountActivation(
            id: null,
            email: $email,
            token: $this->uuid->uuid7(),
            createdAt: new DateTimeImmutable(),
        );

        $this->accountActivationRepository->insert($activation);

        $this->activityLogger->info(
            IdentityLogMessage::ACTIVITY_REGISTER_REQUESTED,
            [
                'emailHash' => EmailHasher::hash($email->toString(), $this->emailHashSaltProvider->salt()),
            ],
        );

        $this->activationTokenService->sendEmail($activation);
    }
}
