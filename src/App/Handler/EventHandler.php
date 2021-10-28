<?php declare(strict_types=1);

namespace App\Handler;

use App\Model\Event;
use App\Model\User;
use DateInterval;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Hydrator\ClassMethodsHydrator;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventHandler implements RequestHandlerInterface
{
    public function __construct(
        private ClassMethodsHydrator $hydrator,
        private TemplateRendererInterface $template,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var Event $event */
        $event = $request->getAttribute(Event::class);

        /** @var User $user */
        $user = $request->getAttribute(User::class);

        $participants = $request->getAttribute('participants');
        $data = $this->hydrator->extract($event);

        $endTime = clone $event->getStartTime();
        // TODO: Move To Model
        $data['endTime'] = $endTime->add(new DateInterval('P' . $event->getDuration() . 'D'));
        $data['eventUser'] = $user->getName();
        $data['eventUserId'] = $user->getId();
        $data['participants'] = $participants;

        return new HtmlResponse($this->template->render('app::event', $data));
    }
}
