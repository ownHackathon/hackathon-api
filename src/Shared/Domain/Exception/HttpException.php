<?php declare(strict_types=1);

namespace Shared\Domain\Exception;

use Monolog\Level;
use RuntimeException;
use Shared\Domain\Enum\Message\LogMessage;
use Shared\Domain\Enum\Message\StatusMessage;
use Throwable;

abstract class HttpException extends RuntimeException
{
    protected array $context = [];
    protected StatusMessage $responseMessage;
    protected Level $logLevel;

    public function __construct(
        LogMessage $logMessage,
        StatusMessage $responseMessage,
        array $context = [],
        Level $loglevel = Level::Notice,
        ?Throwable $previous = null
    ) {
        parent::__construct($logMessage->value, $this->getHttpStatusCode(), $previous);
        $this->context = $context;
        $this->responseMessage = $responseMessage;
        $this->logLevel = $loglevel;
    }

    abstract public function getHttpStatusCode(): int;

    public function getContext(): array
    {
        return $this->context;
    }

    public function getResponseMessage(): StatusMessage
    {
        return $this->responseMessage;
    }

    public function getLogLevel(): Level
    {
        return $this->logLevel;
    }
}
