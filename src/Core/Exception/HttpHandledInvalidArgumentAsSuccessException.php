<?php declare(strict_types=1);

namespace ownHackathon\Core\Exception;

use Fig\Http\Message\StatusCodeInterface;
use Monolog\Level;
use ownHackathon\Core\Enum\Message\LogMessage;
use ownHackathon\Core\Enum\Message\StatusMessage;
use Throwable;

final class HttpHandledInvalidArgumentAsSuccessException extends HttpException
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
        return StatusCodeInterface::STATUS_OK;
    }
}
