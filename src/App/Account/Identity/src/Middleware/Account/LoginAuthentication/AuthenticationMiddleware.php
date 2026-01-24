<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware\Account\LoginAuthentication;

use Exdrals\Mailing\Domain\EmailType;
use DateTimeImmutable;
use Exdrals\Identity\Domain\AccountInterface;
use Exdrals\Identity\Infrastructure\Persistence\Repository\Account\AccountRepositoryInterface;
use Exdrals\Identity\Infrastructure\Service\Authentication\AuthenticationService;
use Exdrals\Mailing\Exception\InvalidArgumentException;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shared\Domain\Enum\Message\LogMessage;
use Shared\Domain\Enum\Message\StatusMessage;
use Shared\Domain\Exception\HttpUnauthorizedException;

use function array_key_exists;

readonly class AuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AuthenticationService $service,
        private AccountRepositoryInterface $accountRepository,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        if (!array_key_exists('email', $data)) {
            throw new HttpUnauthorizedException(
                LogMessage::REQUIRED_EMAIL_MISSING,
                StatusMessage::INVALID_DATA
            );
        }

        try {
            $email = new EmailType($data['email']);
        } catch (InvalidArgumentException) {
            throw new HttpUnauthorizedException(
                LogMessage::EMAIL_INVALID,
                StatusMessage::INVALID_DATA,
                [
                    'E-Mail:' => $data['email'] ?? 'unknown',
                ],
                Level::Warning
            );
        }

        $account = $this->accountRepository->findByEmail($email);

        if (!($account instanceof AccountInterface)) {
            throw new HttpUnauthorizedException(
                LogMessage::ACCOUNT_NOT_FOUND,
                StatusMessage::INVALID_DATA,
                [
                    'E-Mail:' => $email->toString(),
                ],
                Level::Warning
            );
        }

        if (!$this->service->isPasswordMatch($data['password'], $account->password)) {
            throw new HttpUnauthorizedException(
                LogMessage::PASSWORD_INCORRECT,
                StatusMessage::INVALID_DATA,
                [
                    'E-Mail:' => $email->toString(),
                ],
                Level::Warning
            );
        }

        $account = $account->with(lastActionAt: new DateTimeImmutable());

        $this->accountRepository->update($account);

        return $handler->handle($request->withAttribute(AccountInterface::AUTHENTICATED, $account));
    }
}
