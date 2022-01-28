<?php declare(strict_types=1);

namespace App\Handler;

use Administration\Service\TemplateService;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class IndexHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateService $service
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new HtmlResponse($this->service->getTemplateContent('layout', 'default'));
    }
}
