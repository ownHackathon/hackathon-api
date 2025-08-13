<?php declare(strict_types=1);

namespace ownHackathon\App\DTO;

use OpenApi\Attributes as OA;
use ownHackathon\Core\Message\OADataType;

#[OA\Schema()]
readonly class EMailDTO
{
    public function __construct(
        #[OA\Property(
            description: 'The E-Mail',
            type: OADataType::STRING,
        )]
        public string $email,
    ) {
    }

    public static function fromString(string $email): self
    {
        return new self($email);
    }
}
