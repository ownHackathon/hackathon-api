<?php declare(strict_types=1);

namespace Shared\Infrastructure\Factory;

use Exdrals\Identity\DTO\Response\HttpResponseMessage;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Monolog\Level;
use Psr\Log\LoggerInterface;
use Shared\Domain\Enum\Message\StatusMessage;
use Shared\Domain\Exception\HttpException;
use Throwable;

use function sprintf;

final readonly class ErrorResponseFactory
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function createFromThrowable(Throwable $e): JsonResponse
    {
        $statusCode = StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR;
        $responseMessage = StatusMessage::INTERNAL_SERVER_ERROR;


        if ($e instanceof HttpException) {
            $statusCode = $e->getHttpStatusCode();
            $logLevel = $e->getLogLevel();
            $logContext = $e->getContext();
            $responseMessage = $e->getResponseMessage();

            $this->logger->log(
                $logLevel->value,
                sprintf('[%d] %s', $statusCode, $e->getMessage()),
                $logContext
            );
        } else {
            $this->logger->log(
                Level::Critical,
                sprintf('[%d] Unhandled exception %s', $statusCode, $e->getMessage()),
                ['exception' => $e]
            );
        }

        $message = HttpResponseMessage::create($statusCode, $responseMessage);
        return new JsonResponse($message, $message->statusCode);
    }
}
