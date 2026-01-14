<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account\Validation;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use ownHackathon\App\DTO\Response\HttpResponseMessage;
use ownHackathon\App\Validator\EMailValidator;
use ownHackathon\Core\Exception\HttpInvalidArgumentException;
use ownHackathon\Core\Message\ResponseMessage;
use ownHackathon\Core\Type\Email;
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
        $message = HttpResponseMessage::create(HTTP::STATUS_BAD_REQUEST, ResponseMessage::EMAIL_INVALID);

        $data = $request->getParsedBody();

        $this->mailValidator->setData($data);

        if (!$this->mailValidator->isValid()) {
            throw new HttpInvalidArgumentException(
                'Invalid E-Mail',
                ResponseMessage::DATA_INVALID,
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
