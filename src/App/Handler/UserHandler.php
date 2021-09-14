<?php declare(strict_types=1);

namespace App\Handler;

use App\Model\User;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $template,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var User $user */
        $user = $request->getAttribute('user');

        $active = $user->isActive() ? 'Ja' : 'Nein';

        $data = [
            'name' => $user->getName(),
            'lastLogin' => $user->getLastLogin(),
            'active' => $active,
        ];

        return new HtmlResponse($this->template->render('app::user', $data));
    }
}
