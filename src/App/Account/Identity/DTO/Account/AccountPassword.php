<?php declare(strict_types=1);

namespace Exdrals\Identity\DTO\Account;

use Exdrals\Core\Shared\Domain\Enum\DataType;
use OpenApi\Attributes as OA;

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
