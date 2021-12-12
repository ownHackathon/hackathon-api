<?php declare(strict_types=1);

namespace App\Handler;

use App\Model\Event;
use Laminas\Diactoros\Response\RedirectResponse;
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
