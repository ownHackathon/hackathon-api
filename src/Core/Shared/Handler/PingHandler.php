<?php declare(strict_types=1);

namespace ownHackathon\Core\Shared\Handler;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Laminas\Diactoros\Response\JsonResponse;
use OpenApi\Attributes as OA;
use ownHackathon\Core\Shared\Domain\Enum\DataType;
use ownHackathon\Core\Shared\Domain\Message\StatusMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
                description: StatusMessage::SUCCESS,
                content: [
                    new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: 'ack',
                                description: 'actually request time',
                                type: DataType::INTEGER->value,
                            ),
                            new OA\Property(property: 'message', type: DataType::STRING->value, example: 'pong'),
                            new OA\Property(property: 'php_version', type: DataType::STRING->value, example: '8.4.15'),
                        ]
                    ),
                ]
            ),
        ]
    )]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse([
            'ack' => time(),
            'message' => 'pong',
            'php_version' => PHP_VERSION,
        ]);
    }
}
