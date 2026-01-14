<?php declare(strict_types=1);

namespace ownHackathon\App\DTO\Response;

use OpenApi\Attributes as OA;
use ownHackathon\Core\Enum\DataType;

#[OA\Schema()]
readonly class HttpResponseMessage
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
