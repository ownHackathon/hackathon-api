<?php declare(strict_types=1);

namespace App\Account\Identity\DTO\Account;

use App\Account\Identity\Domain\AccountInterface;
use Core\Serialization\DataType;
use OpenApi\Attributes as OA;

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
