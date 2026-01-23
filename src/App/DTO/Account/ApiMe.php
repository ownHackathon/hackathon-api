<?php declare(strict_types=1);

namespace App\DTO\Account;

use Core\Entity\Account\AccountInterface;
use OpenApi\Attributes as OA;

#[OA\Schema()]
readonly class ApiMe
{
    public function __construct(
        public AccountInterface $account,
        public bool $hasWorkspace,
    ) {
    }
}
