<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account\Validation;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use InvalidArgumentException;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use ownHackathon\App\DTO\HttpResponseMessage;
use ownHackathon\App\Validator\EMailValidator;
use ownHackathon\Core\Message\ResponseMessage;
use ownHackathon\Core\Type\Email;

readonly class EmailInputValidatorMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EMailValidator $mailValidator,
        private LoggerInterface $logger,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $message = HttpResponseMessage::create(HTTP::STATUS_BAD_REQUEST, ResponseMessage::EMAIL_INVALID);

        $data = $request->getParsedBody();

        $this->mailValidator->setData($data);

        if (!$this->mailValidator->isValid()) {
            $this->logger->notice('Invalid E-Mail', [
                'E-Mail:' => $data['email'] ?? null,
                'Validator Message:' => $this->mailValidator->getMessages(),
            ]);

            return new JsonResponse($message, $message->statusCode);
        }

        try {
            $email = new Email($data['email']);
        } catch (InvalidArgumentException $exception) {
            $this->logger->notice('Invalid E-Mail', [
                'E-Mail:' => $data['email'] ?? null,
                'Exception Message:' => $exception->getMessage(),
            ]);

            return new JsonResponse($message, $message->statusCode);
        }

        return $handler->handle($request->withAttribute(Email::class, $email));
    }
}
