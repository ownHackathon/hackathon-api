<?php declare(strict_types=1);

namespace App\Middleware;

use App\Model\CsrfToken;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function array_key_exists;

class CsrfTokenMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $header = $request->getHeaders();

        if (!array_key_exists('x-csrf-jwt', $header)) {
            return $handler->handle($request);
        }

        $csrfToken = new CsrfToken();
        $csrfToken->setCsrfToken($header['x-csrf-jwt'][0]);

        return $handler->handle($request->withAttribute(CsrfToken::class, $csrfToken));
    }
}
