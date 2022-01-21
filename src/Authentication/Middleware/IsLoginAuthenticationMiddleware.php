<?php declare(strict_types=1);

namespace Authentication\Middleware;

use App\Model\User;
use Authentication\Exception\InvalidAuthenticationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class IsLoginAuthenticationMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var null|User $user */
        $user = $request->getAttribute(User::USER_ATTRIBUTE);

        if (null === $user) {
            throw new InvalidAuthenticationException();
        }

        return $handler->handle($request);
    }
}
