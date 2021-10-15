<?php declare(strict_types=1);

namespace App\Handler;

use App\Model\Project;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Hydrator\ClassMethodsHydrator;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ProjectHandler implements RequestHandlerInterface
{
    public function __construct(
        private ClassMethodsHydrator $hydrator,
        private TemplateRendererInterface $template,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $project = $request->getAttribute(Project::class);
        $participant = $request->getAttribute('user');

        $data = array_merge($this->hydrator->extract($project), $this->hydrator->extract($participant));

        return new HtmlResponse($this->template->render('app::project', $data));
    }
}