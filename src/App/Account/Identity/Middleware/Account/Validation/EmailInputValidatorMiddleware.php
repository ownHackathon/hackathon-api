<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware\Account\Validation;

use Exdrals\Core\Mailing\Domain\EmailType;
use Exdrals\Core\Mailing\Exception\InvalidArgumentException;
use Exdrals\Core\Mailing\Infrastructure\Validator\EMailValidator;
use Exdrals\Core\Shared\Domain\Exception\HttpInvalidArgumentException;
use Exdrals\Core\Shared\Infrastructure\DTO\HttpResponseMessage;
use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Fig\Http\Message\StatusCodeInterface as HTTP;
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
