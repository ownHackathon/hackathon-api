<?php declare(strict_types=1);

namespace Authentication\Handler;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserRegisterSubmitHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $validationMessages = $request->getAttribute('validationMessages');

        if (null !== $validationMessages) {
            $data = $request->getParsedBody();

            $data['validationMessages'] = $validationMessages;

            return new JsonResponse(['message' => $validationMessages], HTTP::STATUS_NOT_ACCEPTABLE);
        }

        return new JsonResponse(['message' => 'OK'], HTTP::STATUS_OK);
    }
}
