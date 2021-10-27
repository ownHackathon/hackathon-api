<?php declare(strict_types=1);

namespace App\Middleware;

use App\Model\Project;
use App\Model\ProjectCategoryRating;
use App\Rating\ProjectRatingCalculator;
use App\Service\RatingService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ProjectCategoryRatingMiddleware implements MiddlewareInterface
{
    public function __construct(
        private RatingService $ratingService,
        private ProjectRatingCalculator $projectRatingCalculator,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $isRatingReleased = $request->getAttribute('isRatingReleased');

        if (!$isRatingReleased) {
            return $handler->handle($request);
        }

        /** @var Project $project */
        $project = $request->getAttribute(Project::class);

        $projectCategoryRating = $this->ratingService->findProjectCategoryRatingByProjectId($project->getId());

        if ($projectCategoryRating) {
            $request->withAttribute('projectCategoryRatingResult', $this->projectRatingCalculator->calculateProjectRating($projectCategoryRating));
        }

        return $handler->handle($request->withAttribute(ProjectCategoryRating::class, $projectCategoryRating));
    }
}
