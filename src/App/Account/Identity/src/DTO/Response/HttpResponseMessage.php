<?php declare(strict_types=1);

namespace Exdrals\Identity\DTO\Response;

use Fig\Http\Message\StatusCodeInterface as Http;
use OpenApi\Attributes as OA;
use Shared\Domain\Enum\DataType;
use Shared\Domain\Enum\Message\StatusMessage;

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
            example: StatusMessage::BAD_REQUEST->value
        )]
        public StatusMessage $message,
    ) {
    }

    public static function create(int $statusCode, StatusMessage $message): self
    {
        return new self($statusCode, $message);
    }
}
