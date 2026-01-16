<?php declare(strict_types=1);

namespace Core\Middleware;

use Core\Dto\SimpleMessageDto;
use Core\Entity\User;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class IsLoggedInAuthenticationMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /**
         * @var null|User $user
         */
        $user = $request->getAttribute(User::AUTHENTICATED_USER);

        if (!$user) {
            return new JsonResponse(new SimpleMessageDto('Authentication is required'), HTTP::STATUS_UNAUTHORIZED);
        }

        return $handler->handle($request);
    }
}
