<?php declare(strict_types=1);

namespace App\Handler;

use Administration\Service\TemplateService;
use App\Model\Event;
use App\Model\Topic;
use App\Model\User;
use DateInterval;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Hydrator\ClassMethodsHydrator;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EventHandler implements RequestHandlerInterface
{
    public function __construct(
        private ClassMethodsHydrator $hydrator,
        private TemplateService $service,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var Event $event */
        $event = $request->getAttribute(Event::class);

        /** @var User $user */
        $user = $request->getAttribute(User::class);

        $endTime = clone $event->getStartTime();
        // TODO: Move To Model

        $data = $this->hydrator->extract($event);
        $data['endTime'] = $endTime->add(new DateInterval('P' . $event->getDuration() . 'D'));
        $data['eventUser'] = $user->getName();
        $data['eventUserId'] = $user->getId();
        $data['participants'] = $request->getAttribute('participants');
        $data['loggedInUser'] = $request->getAttribute(User::USER_ATTRIBUTE);
        $data['topics'] = $request->getAttribute('topics');
        $data['topic'] = $request->getAttribute(Topic::class);

        return new JsonResponse($data);
    }
}
