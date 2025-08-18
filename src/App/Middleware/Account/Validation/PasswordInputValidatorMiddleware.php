<?php declare(strict_types=1);

namespace ownHackathon\App\Middleware\Account\Validation;

use ownHackathon\App\Validator\PasswordValidator;
use ownHackathon\Core\Exception\HttpInvalidArgumentException;
use ownHackathon\Core\Message\ResponseMessage;
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
                'Invalid Password',
                ResponseMessage::DATA_INVALID,
                [
                    'Validator Message:' => $this->validator->getMessages(),
                ]
            );
        }

        return $handler->handle($request);
    }
}
