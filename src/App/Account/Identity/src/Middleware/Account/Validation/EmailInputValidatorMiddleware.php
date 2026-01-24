<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware\Account\Validation;

use Exdrals\Mailing\Domain\EmailType;
use Exdrals\Mailing\Exception\InvalidArgumentException;
use Exdrals\Mailing\Infrastructure\Validator\EMailValidator;
use Exdrals\Identity\DTO\Response\HttpResponseMessage;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shared\Domain\Enum\Message\LogMessage;
use Shared\Domain\Enum\Message\StatusMessage;
use Shared\Domain\Exception\HttpInvalidArgumentException;

readonly class EmailInputValidatorMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EMailValidator $mailValidator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $message = HttpResponseMessage::create(HTTP::STATUS_BAD_REQUEST, StatusMessage::EMAIL_INVALID);

        $data = $request->getParsedBody();

        $this->mailValidator->setData($data);

        if (!$this->mailValidator->isValid()) {
            throw new HttpInvalidArgumentException(
                LogMessage::EMAIL_INVALID,
                StatusMessage::INVALID_DATA,
                [
                    'E-Mail:' => $data['email'] ?? null,
                    'Validator Message:' => $this->mailValidator->getMessages(),
                ]
            );
        }

        try {
            $email = new EmailType($data['email']);
        } catch (InvalidArgumentException) {
            throw new HttpInvalidArgumentException(
                LogMessage::EMAIL_INVALID,
                StatusMessage::INVALID_DATA,
                [
                    'E-Mail:' => $data['email'] ?? 'unknown',
                ]
            );
        }

        return $handler->handle($request->withAttribute(EmailType::class, $email));
    }
}
