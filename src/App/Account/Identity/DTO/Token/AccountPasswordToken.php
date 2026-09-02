<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\DTO\Token;

use OpenApi\Attributes as OA;
use ownHackathon\Core\Serialization\DataType;

#[OA\Schema()]
readonly final class AccountPasswordToken
{
    public function __construct(
        #[OA\Property(
            description: 'Token to set a new password',
            type: DataType::STRING->value,
        )]
        public string $accountPasswordToken,
    ) {
    }

    public static function fromString(#[\SensitiveParameter] string $accountPasswordToken): self
    {
        return new self($accountPasswordToken);
    }
}
