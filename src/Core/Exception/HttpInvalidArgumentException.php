<?php declare(strict_types=1);

namespace ownHackathon\Core\Exception;

use Fig\Http\Message\StatusCodeInterface;
use Monolog\Level;
use Throwable;

final class HttpInvalidArgumentException extends HttpException
{
    public function __construct(
        string $logMessage,
        string $responseMessage = '',
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
