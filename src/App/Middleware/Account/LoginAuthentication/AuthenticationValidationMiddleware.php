<?php declare(strict_types=1);

namespace App\Middleware\Account\LoginAuthentication;

use App\Validator\AuthenticationValidator;
use Core\Enum\Message\LogMessage;
use Core\Enum\Message\StatusMessage;
use Core\Exception\HttpUnauthorizedException;
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
