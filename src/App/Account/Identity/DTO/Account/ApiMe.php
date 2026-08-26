<?php declare(strict_types=1);

namespace Exdrals\Identity\DTO\Account;

use Exdrals\Core\Shared\Domain\Enum\DataType;
use Exdrals\Identity\Domain\AccountInterface;
use OpenApi\Attributes as OA;

#[OA\Schema(required: ['account', 'hasWorkspace'])]
readonly class ApiMe
{
    public function __construct(
        #[OA\Property(ref: Account::class)]
        public AccountInterface $account,
        #[OA\Property(type: DataType::BOOLEAN->value, example: true)]
        public bool $hasWorkspace,
    ) {
    }
}
