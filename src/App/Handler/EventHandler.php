<?php declare(strict_types=1);

namespace App\Handler;

use App\Model\Event;
use App\Model\User;
use DateInterval;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use DateTime;

class EventHandler implements RequestHandlerInterface
{
    public function __construct(
        private TemplateRendererInterface $template,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var Event $event */
        $event = $request->getAttribute(Event::class);
        /** @var User $user */
        $user = $request->getAttribute('eventUser');

        $eventTime = new DateTime($event->getStartTime());
        $eventTime->add(new DateInterval('P' . $event->getDuration() . 'D'));
        $endTime = $eventTime->format('Y-m-d H:i:s');

        $active = $event->isActive() ? 'Ja' : 'Nein';

        $data = [
            'name' => $event->getName(),
            'description' => $event->getDescription(),
            'eventText' => $event->getEventText(),
            'createTime' => $event->getCreateTime(),
            'startTime' => $event->getStartTime(),
            'duration' => $event->getDuration(),
            'endTime' => $endTime,
            'active' => $active,
            'user' => $user->getName(),
            'userId' => $user->getId(),
        ];

        return new HtmlResponse($this->template->render('app::event', $data));
    }
}
