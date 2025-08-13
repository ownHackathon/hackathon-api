<?php declare(strict_types=1);

namespace ownHackathon\App\DTO;

use ownHackathon\Core\Message\OADataType;
use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class AccountPasswordDTO
{
    public function __construct(
        #[OA\Property(
            description: 'The Password',
            type: OADataType::STRING,
        )]
        public string $password,
    ) {
    }

    public static function fromString(string $password): self
    {
        return new self($password);
    }
}
