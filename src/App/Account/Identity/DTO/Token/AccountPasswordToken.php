<?php declare(strict_types=1);

namespace Exdrals\Identity\DTO\Token;

use Exdrals\Core\Shared\Domain\Enum\DataType;
use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class AccountPasswordToken
{
    public function __construct(
        #[OA\Property(
            description: 'Token to set a new password',
            type: DataType::STRING->value,
        )]
        public string $accountPasswordToken,
    ) {
    }

    public static function fromString(string $accountPasswordToken): self
    {
        return new self($accountPasswordToken);
    }
}
