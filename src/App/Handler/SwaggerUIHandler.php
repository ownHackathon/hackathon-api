<?php declare(strict_types=1);

namespace App\Handler;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Firebase\JWT\JWT;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenApi\Attributes as OA;

#[OA\Info(title: 'My Swagger', version: '0.0.1')]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    name: 'Authorization',
    in: 'header',
    bearerFormat: 'JW1T',
    scheme: 'bearer',
)
]
#[OA\OpenApi(
    security: [['bearerAuth' => []]],
)]
class SwaggerUIHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $indexFile = ROOT_DIR . 'public/swaggerui/index.html';

        if (file_exists($indexFile)) {
            return new HtmlResponse(file_get_contents($indexFile));
        }

        return new JsonResponse('', HTTP::STATUS_NO_CONTENT);
    }
}
