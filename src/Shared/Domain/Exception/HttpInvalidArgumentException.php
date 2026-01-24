<?php declare(strict_types=1);

namespace Shared\Domain\Exception;

use Fig\Http\Message\StatusCodeInterface;
use Monolog\Level;
use Shared\Domain\Enum\Message\LogMessage;
use Shared\Domain\Enum\Message\StatusMessage;
use Throwable;

final class HttpInvalidArgumentException extends HttpException
{
    public function __construct(
        LogMessage $logMessage,
        StatusMessage $responseMessage,
        array $context = [],
        Level $loglevel = Level::Notice,
        ?Throwable $previous = null
    ) {
        parent::__construct($logMessage, $responseMessage, $context, $loglevel, $previous);
    }

    public function getHttpStatusCode(): int
    {
        return StatusCodeInterface::STATUS_BAD_REQUEST;
    }
}
