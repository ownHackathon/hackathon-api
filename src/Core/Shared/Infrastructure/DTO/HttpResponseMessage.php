<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Infrastructure\DTO;

use Exdrals\Core\Shared\Domain\Enum\DataType;
use Exdrals\Core\Shared\Domain\Message\StatusMessage;
use Fig\Http\Message\StatusCodeInterface as Http;
use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class HttpResponseMessage
{
    public function __construct(
        #[OA\Property(
            description: 'The Http Status Code',
            type: DataType::INTEGER->value,
            example: HTTP::STATUS_BAD_REQUEST
        )]
        public int $statusCode,
        #[OA\Property(
            description: 'The Message',
            type: DataType::STRING->value,
            example: StatusMessage::BAD_REQUEST
        )]
        public string $message,
    ) {
    }

    public static function create(int $statusCode, string $message): self
    {
        return new self($statusCode, $message);
    }
}
