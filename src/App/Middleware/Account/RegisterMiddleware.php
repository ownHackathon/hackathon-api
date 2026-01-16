<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account;

use DateTimeImmutable;
use ownHackathon\App\Entity\Account\AccountActivation;
use ownHackathon\App\Service\Account\AccountService;
use ownHackathon\App\Service\Token\ActivationTokenService;
use ownHackathon\Core\Repository\AccountActivationRepositoryInterface;
use ownHackathon\Core\Type\Email;
use ownHackathon\Core\Utils\UuidFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

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
            $this->logger->warning('An account with the same email address already exists.', [
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
