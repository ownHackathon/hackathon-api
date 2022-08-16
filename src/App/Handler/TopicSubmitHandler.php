<?php declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Template\TemplateRendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function array_merge;

class TopicSubmitHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly TemplateRendererInterface $template,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();

        $topicEntriesStatistic = $request->getAttribute('topicEntriesStatistic');

        $data = array_merge($data, $topicEntriesStatistic);
        $data['validationMessages'] = $request->getAttribute('validationMessages');

        if (null === $data['validationMessages']) {
            $data['info'] = 'Vielen Dank für den Themenvorschlag.';
        }

        return new HtmlResponse($this->template->render('app::topic_submit', $data));
    }
}
