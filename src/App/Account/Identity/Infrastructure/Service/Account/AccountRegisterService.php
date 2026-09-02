<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Infrastructure\Service\Account;

use DateTimeImmutable;
use ownHackathon\App\Account\Identity\Application\Port\AccountRegisterServiceInterface;
use ownHackathon\App\Account\Identity\Domain\AccountActivation;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityLogMessage;
use ownHackathon\App\Account\Identity\Domain\Repository\AccountActivationRepositoryInterface;
use ownHackathon\App\Account\Identity\Infrastructure\Service\Token\ActivationTokenService;
use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\Core\Observability\EmailHasher;
use ownHackathon\Core\SharedKernel\Utils\UuidFactoryInterface;
use Psr\Log\LoggerInterface;

readonly final class AccountRegisterService implements AccountRegisterServiceInterface
{
    public function __construct(
        private AccountService $accountService,
        private AccountActivationRepositoryInterface $accountActivationRepository,
        private ActivationTokenService $activationTokenService,
        private UuidFactoryInterface $uuid,
        private LoggerInterface $activityLogger,
        private string $emailHashSalt,
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
                'emailHash' => EmailHasher::hash($email->toString(), $this->emailHashSalt),
            ],
        );

        $this->activationTokenService->sendEmail($activation);
    }
}
