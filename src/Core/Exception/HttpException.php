<?php declare(strict_types=1);

namespace Core\Exception;

use Monolog\Level;
use Core\Enum\Message\LogMessage;
use Core\Enum\Message\StatusMessage;
use RuntimeException;
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
