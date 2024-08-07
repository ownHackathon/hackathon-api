<?php declare(strict_types=1);

namespace App\Handler\Topic;

use App\Dto\Topic\TopicCreateFailureMessageDto;
use App\Dto\Topic\TopicCreateRequestDto;
use App\Dto\Topic\TopicCreateResponseDto;
use App\Entity\Topic;
use App\Service\EMail\EMailServiceInterface;
use Core\Dto\SimpleMessageDto;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class TopicCreateHandler implements RequestHandlerInterface
{
    public function __construct(
        private EMailServiceInterface $mailService,
    ) {
    }

    /**
     *  Create a new hackathon topic
     */
    #[OA\Post(path: '/api/topic', tags: ['Topics'], deprecated: true)]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: TopicCreateRequestDto::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_CREATED,
        description: 'Topic created',
        content: new OA\JsonContent(ref: TopicCreateResponseDto::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_UNAUTHORIZED,
        description: 'Incorrect authorization or expired',
        content: new OA\JsonContent(ref: SimpleMessageDto::class)
    )]
    #[OA\Response(
        response: HTTP::STATUS_BAD_REQUEST,
        description: 'Incorrect Request Data',
        content: new OA\JsonContent(ref: TopicCreateFailureMessageDto::class)
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var Topic $topic */
        $topic = $request->getAttribute(Topic::class);

        $this->mailService->send($topic);

        $topic = new TopicCreateResponseDto($topic);

        return new JsonResponse($topic, HTTP::STATUS_CREATED);
    }
}
