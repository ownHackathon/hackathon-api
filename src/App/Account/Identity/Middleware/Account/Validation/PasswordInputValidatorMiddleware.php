<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Middleware\Account\Validation;

use ownHackathon\App\Account\Identity\Domain\Message\IdentityLogMessage;
use ownHackathon\App\Account\Identity\Domain\Message\IdentityStatusMessage;
use ownHackathon\App\Account\Identity\DTO\Account\AccountPassword;
use ownHackathon\App\Account\Identity\Infrastructure\Validator\PasswordValidator;
use ownHackathon\Core\Http\Exception\HttpInvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

readonly class PasswordInputValidatorMiddleware implements MiddlewareInterface
{
    public function __construct(
        private PasswordValidator $validator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        if (!is_array($data)) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::PASSWORD_INVALID,
                IdentityStatusMessage::INVALID_DATA,
            );
        }

        try {
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

            $password = AccountPassword::fromString($this->validator->getValues()['password']);
        } catch (HttpInvalidArgumentException $e) {
            throw $e;
        } catch (Throwable) {
            throw new HttpInvalidArgumentException(
                IdentityLogMessage::PASSWORD_INVALID,
                IdentityStatusMessage::INVALID_DATA,
            );
        }
        return $handler->handle($request->withAttribute(AccountPassword::class, $password));
    }
}
