<?php declare(strict_types=1);

namespace ownHackathon\App\DTO;

use OpenApi\Attributes as OA;
use ownHackathon\Core\Message\OADataType;

#[OA\Schema()]
readonly class AccountPasswordTokenDTO
{
    public function __construct(
        #[OA\Property(
            description: 'Token to set a new password',
            type: OADataType::STRING,
        )]
        public string $accountPasswordToken,
    ) {
    }

    public static function fromString(string $accountPasswordToken): self
    {
        return new self($accountPasswordToken);
    }
}
