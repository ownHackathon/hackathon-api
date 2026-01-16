<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account\LoginAuthentication;

use DateTimeImmutable;
use Monolog\Level;
use ownHackathon\App\Service\Authentication\AuthenticationService;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Enum\Message\LogMessage;
use ownHackathon\Core\Enum\Message\StatusMessage;
use ownHackathon\Core\Exception\HttpUnauthorizedException;
use ownHackathon\Core\Repository\AccountRepositoryInterface;
use ownHackathon\Core\Type\Email;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

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

        $email = new Email($data['email']);

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
