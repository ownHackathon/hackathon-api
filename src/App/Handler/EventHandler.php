<?php
declare(strict_types=1);

namespace App\Handler;

use App\Model\Event;
use App\Model\User;
use DateInterval;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Hydrator\ReflectionHydrator;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use DateTime;

class EventHandler implements RequestHandlerInterface
{
    public function __construct(
        private ReflectionHydrator $hydrator,
        private TemplateRendererInterface $template,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var Event $event */
        $event = $request->getAttribute(Event::class);

        /** @var User $user */
        $user = $request->getAttribute('eventUser');

        $participants = $request->getAttribute('participants');
        $time = new DateTime($event->getStartTime());
        $time->add(new DateInterval('P' . $event->getDuration() . 'D'));

        $data = $this->hydrator->extract($event);
        $data['endTime'] = $time->format('Y-m-d H:i:s');
        $data['active'] = $event->isActive() ? 'Ja' : 'Nein';
        $data['eventUser'] = $user->getName();
        $data['eventUserId'] = $user->getId();
        $data['participants'] = $participants;

        return new HtmlResponse($this->template->render('app::event', $data));
    }
}
