<?php declare(strict_types=1);

namespace App\Account\Identity\Middleware;

use App\Account\Identity\Domain\Exception\AccountNotFoundException;
use App\Account\Identity\Domain\Exception\DuplicateAuthException;
use App\Account\Identity\Domain\Exception\DuplicateEMailException;
use App\Account\Identity\Domain\Exception\InvalidRefreshTokenException;
use App\Account\Identity\Domain\Exception\PasswordMismatchException;
use App\Account\Identity\Domain\Exception\SecurityBreachException;
use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Message\IdentityStatusMessage;
use Core\Http\Exception\HttpDuplicateEntryException;
use Core\Http\Exception\HttpHandledInvalidArgumentAsSuccessException;
use Core\Http\Exception\HttpUnauthorizedException;
use Core\Observability\EmailHasher;
use Monolog\Level;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly final class IdentityExceptionMappingMiddleware implements MiddlewareInterface
{
    public function __construct(
        private string $emailHashSalt,
    ) {
    }

    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (InvalidRefreshTokenException $e) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::REFRESH_TOKEN_NOT_FOUND,
                IdentityStatusMessage::TOKEN_NOT_PERSISTENT,
                [
                    'Refresh Token:' => $e->refreshToken,
                ],
                Level::Warning,
            );
        } catch (SecurityBreachException $e) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::REFRESH_TOKEN_CLIENT_MISMATCH,
                IdentityStatusMessage::CLIENT_UNEXPECTED,
                [
                    'expected:' => $e->expectedClientHash,
                    'expected UserAgent' => $e->expectedUserAgent,
                    'current:' => $e->actualClientHash,
                    'current UserAgent:' => $e->actualUserAgent,
                ],
                Level::Warning,
            );
        } catch (AccountNotFoundException $e) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::REFRESH_TOKEN_ACCOUNT_NOT_FOUND,
                IdentityStatusMessage::TOKEN_INVALID,
                [
                    'AccessAuth ID:' => $e->accessAuthId,
                    'Account ID:' => $e->accountId,
                    'emailHash' => EmailHasher::hash($e->email, $this->emailHashSalt),
                ],
                Level::Warning,
            );
        } catch (PasswordMismatchException $e) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::PASSWORD_INCORRECT,
                IdentityStatusMessage::INVALID_DATA,
                [
                    'emailHash' => EmailHasher::hash($e->email, $this->emailHashSalt),
                ],
                Level::Warning,
            );
        } catch (DuplicateAuthException $e) {
            throw new HttpDuplicateEntryException(
                IdentityLogMessage::DUPLICATE_SOURCE_LOGIN,
                IdentityStatusMessage::INVALID_DATA,
                [
                    'accountUuid' => $e->account,
                    'ClientID' => $e->clientId,
                    'ErrorMessage' => $e->errorMessage,
                ],
            );
        } catch (DuplicateEmailException $e) {
            throw new HttpHandledInvalidArgumentAsSuccessException(
                IdentityLogMessage::ACCOUNT_ALREADY_EXISTS,
                IdentityStatusMessage::SUCCESS,
                ['emailHash' => EmailHasher::hash($e->email, $this->emailHashSalt)],
            );
        }
    }
}
