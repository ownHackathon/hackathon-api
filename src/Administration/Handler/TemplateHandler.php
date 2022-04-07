<?php declare(strict_types=1);

namespace Administration\Handler;

use Administration\Service\TemplateService;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TemplateHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateService $service,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $section = $request->getAttribute('section');
        $template = $request->getAttribute('template');

        $templateContent = $this->service->getTemplateContent($section, $template);

        return new HtmlResponse($templateContent);
    }
}
