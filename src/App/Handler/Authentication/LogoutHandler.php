<?php declare(strict_types=1);

namespace App\Handler\Authentication;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\RedirectResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class LogoutHandler implements RequestHandlerInterface
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
            ),
        ]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new RedirectResponse('/', HTTP::STATUS_SEE_OTHER);
    }
}
