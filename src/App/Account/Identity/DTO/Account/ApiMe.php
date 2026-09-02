<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\DTO\Account;

use OpenApi\Attributes as OA;
use ownHackathon\App\Account\Identity\Domain\AccountInterface;
use ownHackathon\Core\Serialization\DataType;

#[OA\Schema(required: ['account', 'hasWorkspace'])]
readonly final class ApiMe
{
    public function __construct(
        #[OA\Property(ref: Account::class)]
        public AccountInterface $account,
        #[OA\Property(type: DataType::BOOLEAN->value, example: true)]
        public bool $hasWorkspace,
    ) {
    }
}
