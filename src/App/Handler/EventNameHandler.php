<?php declare(strict_types=1);

namespace App\Handler;

use App\Model\Event;
use App\Model\User;
use DateInterval;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Hydrator\ClassMethodsHydrator;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventNameHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $event = $request->getAttribute(Event::class);

        return new RedirectResponse('/event/' . $event->getId());
    }
}
