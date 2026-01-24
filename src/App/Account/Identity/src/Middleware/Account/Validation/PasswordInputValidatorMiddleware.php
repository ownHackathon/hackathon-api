<?php declare(strict_types=1);

namespace Exdrals\Identity\Middleware\Account\Validation;

use Exdrals\Identity\Domain\Message\IdentityLogMessage;
use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Exdrals\Identity\Infrastructure\Validator\PasswordValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Exdrals\Shared\Domain\Exception\HttpInvalidArgumentException;

readonly class PasswordInputValidatorMiddleware implements MiddlewareInterface
{
    public function __construct(
        private PasswordValidator $validator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $this->validator->setData($data);

        if (!$this->validator->isValid()) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::PASSWORD_INVALID,
                IdentityStatusMessage::INVALID_DATA,
                [
                    'Validator Message:' => $this->validator->getMessages(),
                ]
            );
        }

        return $handler->handle($request);
    }
}
