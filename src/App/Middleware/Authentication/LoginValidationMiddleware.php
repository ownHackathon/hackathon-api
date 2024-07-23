<?php declare(strict_types=1);

namespace App\Middleware\Authentication;

use App\Dto\UserContent\LoginValidationFailureMessageDto;
use Core\Validator\LoginValidator;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class LoginValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private LoginValidator $validator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $this->validator->setData($data);

        if (!$this->validator->isValid()) {
            // ToDo $this->validator->getMessage()
            return new JsonResponse(
                new LoginValidationFailureMessageDto('Login failed', $data),
                HTTP::STATUS_BAD_REQUEST
            );
        }

        return $handler->handle($request->withParsedBody($this->validator->getValues()));
    }
}
