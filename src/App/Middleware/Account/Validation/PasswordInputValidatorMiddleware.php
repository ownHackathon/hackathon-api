<?php declare(strict_types=1);

namespace App\Middleware\Account\Validation;

use App\Validator\PasswordValidator;
use Core\Enum\Message\LogMessage;
use Core\Enum\Message\StatusMessage;
use Core\Exception\HttpInvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
                LogMessage::PASSWORD_INVALID,
                StatusMessage::INVALID_DATA,
                [
                    'Validator Message:' => $this->validator->getMessages(),
                ]
            );
        }

        return $handler->handle($request);
    }
}
