<?php declare(strict_types=1);

namespace Administration\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TemplateHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $template
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $template = $request->getAttribute('templateName');
        $response = file_get_contents(__DIR__ . '/../../../templates/app/' . $template . '.phtml');
        return new HtmlResponse($response);
    }
}
