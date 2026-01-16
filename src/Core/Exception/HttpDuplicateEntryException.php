<?php declare(strict_types=1);

namespace Core\Exception;

use Fig\Http\Message\StatusCodeInterface;
use Monolog\Level;
use Core\Enum\Message\LogMessage;
use Core\Enum\Message\StatusMessage;
use Throwable;

final class HttpDuplicateEntryException extends HttpException
{
    public function __construct(
        LogMessage $logMessage,
        StatusMessage $responseMessage,
        array $context = [],
        Level $loglevel = Level::Warning,
        ?Throwable $previous = null
    ) {
        parent::__construct($logMessage, $responseMessage, $context, $loglevel, $previous);
    }

    public function getHttpStatusCode(): int
    {
        return StatusCodeInterface::STATUS_BAD_REQUEST;
    }
}
