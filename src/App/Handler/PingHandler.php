<?php declare(strict_types=1);

namespace ownHackathon\App\Handler;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ownHackathon\Core\Message\OADataType;
use ownHackathon\Core\Message\OAMessage;

use function time;

readonly class PingHandler implements RequestHandlerInterface
{
    #[OA\Get(
        path: '/ping',
        summary: 'Returns the current time in Unix format',
        tags: ['System Information'],
        responses: [
            new OA\Response(
                response: HTTP::STATUS_OK,
                description: OAMessage::SUCCESS,
                content: [
                    new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: 'ack',
                                description: 'actually request time',
                                type: OADataType::STRING,
                            ),
                        ]
                    ),
                ]
            ),
        ]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(['ack' => time()]);
    }
}
