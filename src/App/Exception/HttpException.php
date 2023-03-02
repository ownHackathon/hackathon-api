<?php declare(strict_types=1);

namespace App\Exception;

use Exception;
use Fig\Http\Message\StatusCodeInterface as Http;
use JetBrains\PhpStorm\Pure;
use Throwable;

class HttpException extends Exception
{
    protected array $jsonMessage;

    #[Pure] public function __construct(
        array $jsonMessage = [],
        int $code = Http::STATUS_FAILED_DEPENDENCY,
        ?Throwable $previous = null
    ) {
        $this->jsonMessage = $jsonMessage;
        parent::__construct('', $code, $previous);
    }

    public function getJSonMessage(): array
    {
        return $this->jsonMessage;
    }
}
