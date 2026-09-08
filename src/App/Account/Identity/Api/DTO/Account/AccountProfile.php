<?php declare(strict_types=1);

namespace App\Account\Identity\Api\DTO\Account;

use App\Account\Identity\Api\AccountProfileInterface;
use Ramsey\Uuid\UuidInterface;

readonly final class AccountProfile implements AccountProfileInterface
{
    public function __construct(
        public ?int $id,
        public UuidInterface $uuid,
        public string $name,
    ) {
    }
}
