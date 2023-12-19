<?php declare(strict_types=1);

namespace Authentication\Handler;

use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Attributes as OA;
use Fig\Http\Message\StatusCodeInterface as HTTP;

class LogoutHandler implements RequestHandlerInterface
{
    /**
     * not implementet yet
     */
    #[OA\Get(
        path: '/api/logout',
        tags: ['User Control'],
        responses: [
            new OA\Response(
                response: HTTP::STATUS_SEE_OTHER,
                description: 'not implementet yet'
            )
        ]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new RedirectResponse('/', HTTP::STATUS_SEE_OTHER);
    }
}
