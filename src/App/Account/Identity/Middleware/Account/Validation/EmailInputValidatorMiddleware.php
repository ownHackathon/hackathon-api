<?php declare(strict_types=1);

namespace App\Account\Identity\Middleware\Account\Validation;

use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Message\IdentityStatusMessage;
use App\Mailing\Domain\EmailType;
use App\Mailing\Exception\InvalidArgumentException;
use App\Mailing\Infrastructure\Validator\EMailValidator;
use Core\Http\Exception\HttpInvalidArgumentException;
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

    #[\Override]
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
            $result = $this->mailValidator->validate($data);

            if (!$result->valid()) {
                throw new HttpInvalidArgumentException(
                    IdentityLogMessage::EMAIL_INVALID,
                    IdentityStatusMessage::INVALID_DATA,
                    [
                        'E-Mail:' => $data['email'] ?? null,
                        'Validator Message:' => $result->getMessages()->toArray(),
                    ],
                );
            }

            /** @var array{email: string} $data */
            $data = $result->value();

            $email = new EmailType($data['email']);
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
