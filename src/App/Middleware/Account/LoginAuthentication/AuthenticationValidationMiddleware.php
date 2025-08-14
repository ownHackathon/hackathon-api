<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account\LoginAuthentication;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use ownHackathon\App\DTO\HttpFailureMessage;
use ownHackathon\App\Validator\AuthenticationValidator;
use ownHackathon\Core\Message\ResponseMessage;

readonly class AuthenticationValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AuthenticationValidator $validator,
        private LoggerInterface $logger,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $this->validator->setData($data);

        if (!$this->validator->isValid()) {
            $this->logger->notice('Email and/or password are not valid', [
                'E-Mail:' => $data['email'] ?? null,
                'Validator-Message:' => $this->validator->getMessages(),
            ]);

            $message = HttpFailureMessage::create(HTTP::STATUS_UNAUTHORIZED, ResponseMessage::DATA_INVALID);

            return new JsonResponse($message, $message->statusCode);
        }

        return $handler->handle($request->withParsedBody($this->validator->getValues()));
    }
}
