<?php declare(strict_types=1);

namespace Test\Unit\Mock\Handler;

use App\Handler\IndexHandler;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MockIndexHandler extends IndexHandler
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(['test' => true], HTTP::STATUS_OK);
    }
}
