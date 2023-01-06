<?php declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Fig\Http\Message\StatusCodeInterface as HTTP;

use function file_get_contents;

class IndexHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $indexFile = ROOT_DIR . 'public/default.mustache';

        if (file_exists($indexFile)) {
            return new HtmlResponse(file_get_contents($indexFile));
        }

        return new JsonResponse('', HTTP::STATUS_NO_CONTENT);
    }
}
