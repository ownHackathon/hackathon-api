<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Middleware\Account\Authentication;

use ownHackathon\Core\Shared\Domain\Exception\HttpUnauthorizedException;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityLogMessage;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityStatusMessage;
use ownHackathon\App\Account\Identity\DTO\Account\AuthenticationRequest;
use ownHackathon\App\Account\Identity\Infrastructure\Validator\AuthenticationValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
                IdentityLogMessage::EMAIL_INVALID,
                IdentityStatusMessage::INVALID_DATA,
                [
                    'E-Mail:' => $data['email'] ?? null,
                    'Validator-Message:' => $this->validator->getMessages(),
                ]
            );
        }

        $response = AuthenticationRequest::fromArray($this->validator->getValues());

        return $handler->handle($request->withAttribute(AuthenticationRequest::class, $response));
    }
}
