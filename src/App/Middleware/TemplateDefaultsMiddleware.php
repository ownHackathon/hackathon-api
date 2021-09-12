<?php declare(strict_types=1);

namespace App\Middleware;

use App\Model\User;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TemplateDefaultsMiddleware implements MiddlewareInterface
{
    public function __construct(
        private TemplateRendererInterface $templateRenderer
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var User $user */
        $user = $request->getAttribute(User::class);

        $isLoggedIn = false;
        $userName = 'Gast';

        if (isset($user)) {
            $isLoggedIn = true;
            $userName = $user->getName();
        }

        $this->templateRenderer->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'isLoggedIn',
            $isLoggedIn
        );

        $this->templateRenderer->addDefaultParam(
            TemplateRendererInterface::TEMPLATE_ALL,
            'userName',
            $userName
        );

        return $handler->handle($request);
    }
}
