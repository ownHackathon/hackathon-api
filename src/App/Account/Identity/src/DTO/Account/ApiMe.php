<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\DTO\Account;

use Exdrals\Account\Identity\Domain\AccountInterface;
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
