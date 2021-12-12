<?php declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventCreateSubmitHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $template,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();

        $validationMessages = $request->getAttribute('validationMessages');

        if (null !== $validationMessages) {
            $data['validationMessages'] = $validationMessages;

            return new HtmlResponse($this->template->render('app::event_create_form', $data));
        }

        return new RedirectResponse('/event/list', 303);
    }
}
