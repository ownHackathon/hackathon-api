<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Middleware\Account\LoginAuthentication;

use Exdrals\Account\Identity\Infrastructure\Validator\AuthenticationValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shared\Domain\Enum\Message\LogMessage;
use Shared\Domain\Enum\Message\StatusMessage;
use Shared\Domain\Exception\HttpUnauthorizedException;

readonly class AuthenticationValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AuthenticationValidator $validator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $this->validator->setData($data);

        if (!$this->validator->isValid()) {
            throw new HttpUnauthorizedException(
                LogMessage::EMAIL_INVALID,
                StatusMessage::INVALID_DATA,
                [
                    'E-Mail:' => $data['email'] ?? null,
                    'Validator-Message:' => $this->validator->getMessages(),
                ]
            );
        }

        return $handler->handle($request->withParsedBody($this->validator->getValues()));
    }
}
