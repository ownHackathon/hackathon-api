<?php declare(strict_types=1);

namespace Core\Http\DTO;

use Core\Serialization\DataType;
use OpenApi\Attributes as OA;

#[OA\Schema(required: ['statusCode', 'message'])]
readonly final class HttpResponseMessage
{
    public function __construct(
        #[OA\Property(
            description: 'The Http Status Code',
            type: DataType::INTEGER->value,
        )]
        public int $statusCode,
        #[OA\Property(
            description: 'The Message',
            type: DataType::STRING->value,
        )]
        public string $message,
    ) {
    }

    public static function create(int $statusCode, string $message): self
    {
        return new self($statusCode, $message);
    }
}
