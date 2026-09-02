<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Middleware\Account\Validation;

use ownHackathon\App\Account\Identity\Domain\Message\IdentityLogMessage;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityStatusMessage;
use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\App\Mailing\Exception\InvalidArgumentException;
use ownHackathon\App\Mailing\Infrastructure\Validator\EMailValidator;
use ownHackathon\Core\Http\Exception\HttpInvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

readonly final class EmailInputValidatorMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EMailValidator $mailValidator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        if (!is_array($data)) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::EMAIL_INVALID,
                IdentityStatusMessage::INVALID_DATA,
            );
        }

        try {
            $this->mailValidator->setData($data);

            if (!$this->mailValidator->isValid()) {
                throw new HttpInvalidArgumentException(
                    IdentityLogMessage::EMAIL_INVALID,
                    IdentityStatusMessage::INVALID_DATA,
                    [
                        'E-Mail:' => $data['email'] ?? null,
                        'Validator Message:' => $this->mailValidator->getMessages(),
                    ],
                );
            }

            $email = new EmailType($this->mailValidator->getValues()['email']);
        } catch (InvalidArgumentException | HttpInvalidArgumentException $e) {
            if ($e instanceof HttpInvalidArgumentException) {
                throw $e;
            }
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::EMAIL_INVALID,
                IdentityStatusMessage::INVALID_DATA,
                [
                    'E-Mail:' => $data['email'] ?? 'unknown',
                ],
            );
        } catch (Throwable) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::EMAIL_INVALID,
                IdentityStatusMessage::INVALID_DATA,
            );
        }

        return $handler->handle($request->withAttribute(EmailType::class, $email));
    }
}
