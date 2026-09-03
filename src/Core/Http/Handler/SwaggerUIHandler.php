<?php declare(strict_types=1);

namespace Core\Http\Handler;

use Fig\Http\Message\StatusCodeInterface as Http;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[OA\Info(
    version: '0.1.0',
    title: 'App API Overview',
)]
#[OA\SecurityScheme(
    securityScheme: 'accessToken',
    type: 'apiKey',
    name: 'Authorization',
    in: 'header',
)
]
#[OA\SecurityScheme(
    securityScheme: 'refreshToken',
    type: 'apiKey',
    name: 'Authentication',
    in: 'header',
)
]
#[OA\SecurityScheme(
    securityScheme: 'Client-Identification-String',
    type: 'apiKey',
    name: 'x-ident',
    in: 'header',
)]
#[OA\OpenApi(
    servers: [
        new OA\Server(
            url: '/api',
        ),
    ],
)]
readonly final class SwaggerUIHandler implements RequestHandlerInterface
{
    public function __construct(
        private string $indexFile,
    ) {
    }

    #[\Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (file_exists($this->indexFile)) {
            return new HtmlResponse(file_get_contents($this->indexFile));
        }

        return new JsonResponse([], Http::STATUS_NO_CONTENT);
    }
}
