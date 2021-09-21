<?php
declare(strict_types=1);

namespace App\Handler;

use App\Model\Project;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Hydrator\ReflectionHydrator;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ProjectHandler implements RequestHandlerInterface
{
    public function __construct(
        private ReflectionHydrator $hydrator,
        private TemplateRendererInterface $template,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $project = $request->getAttribute(Project::class);
        $data = $this->hydrator->extract($project);

        return new HtmlResponse($this->template->render('app::project', $data));
    }
}