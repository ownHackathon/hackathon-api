<?php
declare(strict_types=1);

namespace App\Handler;

use App\Model\User;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Hydrator\ReflectionHydrator;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserHandler implements RequestHandlerInterface
{
    public function __construct(
        private ReflectionHydrator $hydrator,
        private TemplateRendererInterface $template,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute('user');

        $data = $this->hydrator->extract($user);

        return new HtmlResponse($this->template->render('app::user', $data));
    }
}
