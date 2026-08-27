<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Middleware\Account\Validation;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityLogMessage;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityStatusMessage;
use ownHackathon\App\Mailing\Domain\EmailType;
use ownHackathon\App\Mailing\Exception\InvalidArgumentException;
use ownHackathon\App\Mailing\Infrastructure\Validator\EMailValidator;
use ownHackathon\Core\Shared\Domain\Exception\HttpInvalidArgumentException;
use ownHackathon\Core\Shared\Infrastructure\DTO\HttpResponseMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class EmailInputValidatorMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EMailValidator $mailValidator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $message = HttpResponseMessage::create(HTTP::STATUS_BAD_REQUEST, IdentityStatusMessage::EMAIL_INVALID);

        $data = $request->getParsedBody();

        $this->mailValidator->setData($data);

        if (!$this->mailValidator->isValid()) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::EMAIL_INVALID,
                IdentityStatusMessage::INVALID_DATA,
                [
                    'E-Mail:' => $data['email'] ?? null,
                    'Validator Message:' => $this->mailValidator->getMessages(),
                ]
            );
        }

        try {
            $email = new EmailType($this->mailValidator->getValues()['email']);
        } catch (InvalidArgumentException) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::EMAIL_INVALID,
                IdentityStatusMessage::INVALID_DATA,
                [
                    'E-Mail:' => $data['email'] ?? 'unknown',
                ]
            );
        }

        return $handler->handle($request->withAttribute(EmailType::class, $email));
    }
}
