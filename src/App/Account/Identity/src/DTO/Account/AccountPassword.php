<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\DTO\Account;

use OpenApi\Attributes as OA;
use Shared\Domain\Enum\DataType;

#[OA\Schema()]
readonly class AccountPassword
{
    public function __construct(
        #[OA\Property(
            description: 'The Password',
            type: DataType::STRING->value,
        )]
        public string $password,
    ) {
    }

    public static function fromString(string $password): self
    {
        return new self($password);
    }
}
