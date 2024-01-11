<?php declare(strict_types=1);

namespace App\Middleware\Authentication;

use App\Service\Authentication\ApiAccessService;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class ApiAccessMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ApiAccessService $apiAccessService,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $domain = explode(':', $request->getHeader('Host')[0])[0];

        if (!$this->apiAccessService->hasAccessRights($domain)) {
            return new JsonResponse(['message' => 'No access authorization'], HTTP::STATUS_UNAUTHORIZED);
        }

        return $handler->handle($request);
    }
}
