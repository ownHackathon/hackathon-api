<?php declare(strict_types=1);

namespace Core\Http\Factory;

use Core\Http\DTO\HttpResponseMessage;
use Core\Http\Exception\HttpException;
use Core\SharedKernel\Domain\Message\StatusMessage;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Monolog\Level;
use Psr\Log\LoggerInterface;
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
                $this->sanitizeContext($logContext),
            );
        } else {
            $this->logger->log(
                Level::Critical,
                sprintf('[%d] Unhandled exception %s', $statusCode, $e->getMessage()),
                ['exception' => $e],
            );
        }

        $message = HttpResponseMessage::create($statusCode, $responseMessage);
        return new JsonResponse($message, $message->statusCode);
    }

    private function sanitizeContext(array $context): array
    {
        $sensitiveKeys = ['email', 'e-mail', 'account', 'user', 'benutzer', 'nutzername'];
        $result = [];
        foreach ($context as $key => $value) {
            if (!is_string($key)) {
                $result[$key] = $value;
                continue;
            }
            $normalized = strtolower(str_replace([' ', ':', '-', '_'], '', trim($key)));
            if (in_array($normalized, $sensitiveKeys, true)) {
                continue;
            }
            $result[$key] = $value;
        }
        return $result;
    }
}
