<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account\LoginAuthentication;

use ownHackathon\App\Validator\AuthenticationValidator;
use ownHackathon\Core\Exception\HttpUnauthorizedException;
use ownHackathon\Core\Message\ResponseMessage;
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
                'Email and/or password are not valid',
                ResponseMessage::DATA_INVALID,
                [
                    'E-Mail:' => $data['email'] ?? null,
                    'Validator-Message:' => $this->validator->getMessages(),
                ]
            );
        }

        return $handler->handle($request->withParsedBody($this->validator->getValues()));
    }
}
