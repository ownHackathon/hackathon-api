<?php declare(strict_types=1);

namespace App\Account\Identity\DTO\Token;

use Core\Serialization\DataType;
use OpenApi\Attributes as OA;

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
