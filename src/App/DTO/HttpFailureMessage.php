<?php declare(strict_types=1);

namespace ownHackathon\App\DTO;

use OpenApi\Attributes as OA;
use ownHackathon\Core\Message\OADataType;

#[OA\Schema()]
readonly class HttpFailureMessage
{
    public function __construct(
        #[OA\Property(
            description: 'The Http Status Code',
            type: OADataType::INTEGER,
        )]
        public int $statusCode,
        #[OA\Property(
            description: 'The Failure Message',
            type: OADataType::STRING,
        )]
        public string $message,
    ) {
    }

    public static function create(int $statusCode, string $message): self
    {
        return new self($statusCode, $message);
    }
}
