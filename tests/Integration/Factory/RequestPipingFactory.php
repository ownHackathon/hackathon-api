<?php declare(strict_types=1);

namespace Tests\Integration\Factory;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

class RequestPipingFactory
{
    /**
     * Führt eine Middleware aus und gibt den modifizierten Request zurück.
     *
     * @param class-string<MiddlewareInterface> $middlewareClass Die Klasse der Middleware.
     * @param ServerRequestInterface $originalRequest Der initiale Request.
     *
     * @return ServerRequestInterface Der Request mit den Attributen der Middleware.
     */
    public static function process(
        string $middlewareClass,
        ServerRequestInterface $originalRequest
    ): ServerRequestInterface {
        $container = test()->getContainer();
        $middleware = $container->get($middlewareClass);
        $dummyNextHandler = new class implements RequestHandlerInterface {
            public ?ServerRequestInterface $savedRequest = null;

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $this->savedRequest = $request;
                return new EmptyResponse(HTTP::STATUS_NO_CONTENT);
            }
        };
        $middleware->process($originalRequest, $dummyNextHandler);
        if ($dummyNextHandler->savedRequest === null) {
            throw new RuntimeException(
                'Middleware did not call the next handler. Check if it returned an early response (e.g. 400).'
            );
        }

        return $dummyNextHandler->savedRequest;
    }
}
