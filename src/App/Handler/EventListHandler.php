<?php declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventListHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $template,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data['activeEvents'] = $request->getAttribute('ActiveEvents') ?? [];
        $data['notActiveEvents'] = $request->getAttribute('NotActiveEvents') ?? [];
        return new HtmlResponse($this->template->render('app::event_list', $data));
    }
}
