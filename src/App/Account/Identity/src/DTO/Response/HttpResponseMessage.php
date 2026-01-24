<?php declare(strict_types=1);

namespace Exdrals\Identity\DTO\Response;

use Exdrals\Identity\Domain\Message\IdentityStatusMessage;
use Fig\Http\Message\StatusCodeInterface as Http;
use OpenApi\Attributes as OA;
use Exdrals\Shared\Domain\Enum\DataType;

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
            example: IdentityStatusMessage::BAD_REQUEST
        )]
        public string $message,
    ) {
    }

    public static function create(int $statusCode, string $message): self
    {
        return new self($statusCode, $message);
    }
}
