<?php declare(strict_types=1);

namespace App\Middleware\Topic;

use App\Validator\TopicCreateValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class TopicCreateValidationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TopicCreateValidator $validator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $request->getParsedBody();

        $this->validator->setData($data);

        if (!$this->validator->isValid()) {
            return $handler->handle($request->withAttribute('validationMessages', $this->validator->getMessages()));
        }

        return $handler->handle($request->withParsedBody($this->validator->getValues()));
    }
}
