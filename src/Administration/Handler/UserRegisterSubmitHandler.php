<?php declare(strict_types=1);

namespace Administration\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserRegisterSubmitHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $template,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $validationMessages = $request->getAttribute('validationMessages');

        if (null !== $validationMessages) {
            $data = $request->getParsedBody();

            $data['validationMessages'] = $validationMessages;

            return new HtmlResponse($this->template->render('app::user_register', $data));
        }


        return new RedirectResponse('/', 303);
    }
}
