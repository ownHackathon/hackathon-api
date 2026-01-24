<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Middleware\Account\Validation;

use Exdrals\Account\Identity\Domain\Email;
use Exdrals\Account\Identity\DTO\Response\HttpResponseMessage;
use Exdrals\Account\Identity\Infrastructure\Validator\EMailValidator;
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

        $email = new Email($data['email']);

        return $handler->handle($request->withAttribute(Email::class, $email));
    }
}
