<?php declare(strict_types=1);

namespace App\Account\Identity\Middleware\Account\Authentication;

use App\Account\Identity\Domain\Message\IdentityLogMessage;
use App\Account\Identity\Domain\Message\IdentityStatusMessage;
use App\Account\Identity\DTO\Account\AuthenticationRequest;
use App\Account\Identity\Infrastructure\Validator\AuthenticationValidator;
use Core\Http\Exception\HttpUnauthorizedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

readonly final class AuthenticationValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AuthenticationValidator $validator,
    ) {
    }

    #[\Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        if (!is_array($data)) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::EMAIL_INVALID,
                IdentityStatusMessage::INVALID_DATA,
            );
        }

        try {
            $result = $this->validator->validate($data);

            if (!$result->valid()) {
                throw new HttpUnauthorizedException(
                    IdentityLogMessage::EMAIL_INVALID,
                    IdentityStatusMessage::INVALID_DATA,
                    [
                        'E-Mail:' => $data['email'] ?? null,
                        'Validator-Message:' => $result->getMessages()->toArray(),
                    ],
                );
            }

            $response = AuthenticationRequest::fromArray($result->value());
        } catch (HttpUnauthorizedException $e) {
            throw $e;
        } catch (Throwable) {
            throw new HttpUnauthorizedException(
                IdentityLogMessage::EMAIL_INVALID,
                IdentityStatusMessage::INVALID_DATA,
            );
        }

        return $handler->handle($request->withAttribute(AuthenticationRequest::class, $response));
    }
}
